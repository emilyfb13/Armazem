#INCLUDE 'PROTHEUS.CH'
#INCLUDE 'TOPCONN.CH'
#INCLUDE 'RESTFUL.CH'
#DEFINE CRLF chr(13) + chr(10)
#DEFINE CHAVE 1
#DEFINE VALOR 2

/*Dummy Function
Serve apenas para reservar o nome do nosso '.prw' no RPO
Se necessário, pode ser consumida por um fonte de nosso projeto*/
User Function ApiCentroCusto()
Return

// Declaração da estrutura do Web Service RestFul
WSRESTFUL ApiCentroCusto      DESCRIPTION "Serviço REST para manipulação de Centro de Custo"

    // Definição dos Atributos
    WSDATA num   AS STRING 
    WSDATA nome AS STRING 
    
    // Definição do Método
   
   WSMETHOD GET DESCRIPTION "Retorna os produtos pelo código" WSSYNTAX "/APICENTROCUSTO/ || /APICENTROCUSTO{}" 

END WSRESTFUL

WSMETHOD GET WSRECEIVE num, nome WSSERVICE APICENTROCUSTO

    Local cJson := ""

    Local cQueryCTT     := ArrayToQuery(Self:aQueryString)
    Local aCustos := {}

    aCustos := GetCusto(cQueryCTT)
    cJson := FWJsonSerialize(aCustos)
   
    ::SetContentType("application/json")
    ::SetResponse(cJson)

Return (.T.)


Static Function ArrayToQuery(aQryString)
    
    Local cRet := ""    
    Local nCount  := 1
    Local cFilterNum      := ""
    Local cSort          := ""
    Local cOrder         := ""
    Local cOffset        := ""
    Local cLimit         := ""

    for nCount := 1 to len(aQryString)

        // Cláusula B1_COD IN 
        If aQryString[nCount][CHAVE] == "NUM"
            cFilterNum +=  "'" + UPPER( aQryString[nCount][VALOR]) + "', "
        
        // Cláusula ORDER BY 
        ELSEIF aQryString[nCount][CHAVE] == "SORT"
            
            DO CASE 
                CASE UPPER(aQryString[nCount][VALOR]) == "NUM"
                    cSort += " CTT_CUSTO, "
                CASE UPPER(aQryString[nCount][VALOR]) == "DESCRICAO"
                    cSort += " CTT_DESC01, "                    
            END 
        // Cláusula ORDER BY 
        ELSEIF aQryString[nCount][CHAVE] == "ORDER"
            cOrder := UPPER( aQryString[nCount][VALOR]) 

         // Cláusula RANGE INÍCIO DA PAGINAÇÃO
        ELSEIF aQryString[nCount][CHAVE] == "OFFSET"
            cOffset     =+ UPPER( aQryString[nCount][VALOR])

        //Cláusula RANGE LIMITE DA PAGINAÇÃO
        ELSEIF  aQryString[nCount][CHAVE] == "LIMIT"  
            cLimit       =+ UPPER( aQryString[nCount][VALOR]) 
        EndIf
    next nCount

    // Retira a última vírgula 
    cFilterNum       := SubStr(cFilterNum     , 1, Len(cFilterNum      )  - 2) 
    cFilterNum       := StrTran( cFilterNum, ";", "','" ) 
    
    IF !Empty(cSort)
        cSort      := Alltrim(SubStr(cSort , 1, Len(cSort          )  - 2) )
    else
        cSort      := "CTT_CUSTO"
    EndIf

    cRet :=        " SELECT *
    cRet += CRLF + " FROM   (SELECT ROW_NUMBER() "
    cRet += CRLF + "                  OVER( "
    cRet += CRLF + "                    ORDER BY CTT." + cSort + ") AS NUMBER, "
    cRet += CRLF + "                CTT_CUSTO, CTT_DESC01"
    cRet += CRLF + "         FROM "         + RetSqlName("CTT")  + " CTT "
    cRet += CRLF + "           WHERE   CTT_CLASSE = '2'"

    If ! Empty(cFilterNum)
        cRet += CRLF + "               AND CTT_CUSTO IN   (" + cFilterNum  + ")" 
    EndIf  

    cRet += CRLF +"                    AND CTT.D_E_L_E_T_ = '') AS TBL
    
    If ! Empty(cOffset)  .AND. ! Empty(cLimit)
        
        cRet += CRLF +" WHERE  NUMBER BETWEEN '"  + cOffset + "' AND '" + cLimit + "'
    
    ENDIF    
  
  cRet += CRLF +" ORDER  BY " + cSort + " " + cOrder + " ;"
    
    MemoWrite( SuperGetMV("BE_LOGDIR", , "\logbio\") + FunName() + "_ApiCentroCusto_QryCTT.sql", cRet)

Return cRet


Static Function GetCusto(cQueryCTT)

    Local aArea     := GetArea()
    Local aRet      := {}

    If Select("QRYCTT") <> 0 
		QRYCTT->(DbCloseArea())
	EndIf

    TCQUERY (cQueryCTT) ALIAS "QRYCTT" NEW

	While  ! QRYCTT->( Eof() )

        aAdd( aRet, Custos():New( QRYCTT->CTT_CUSTO ))
		QRYCTT->( dbSkip())

	EndDo
   
    RestArea(aArea)

Return aRet
