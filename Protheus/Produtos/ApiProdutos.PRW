#INCLUDE 'PROTHEUS.CH'
#INCLUDE 'TOPCONN.CH'
#INCLUDE 'RESTFUL.CH'
#DEFINE CRLF chr(13) + chr(10)
#DEFINE CHAVE 1
#DEFINE VALOR 2

/*Dummy Function
Serve apenas para reservar o nome do nosso '.prw' no RPO
Se necessário, pode ser consumida por um fonte de nosso projeto*/
User Function ApiProdutos()
Return

// Declaração da estrutura do Web Service RestFul
WSRESTFUL ApiProdutos       DESCRIPTION "Serviço REST para manipulação de Produtos"

    // Definição dos Atributos
    WSDATA id   AS STRING 
    WSDATA tipo AS STRING 
    
    // Definição do Método
   
   WSMETHOD GET DESCRIPTION "Retorna os produtos pelo código" WSSYNTAX "/APIPRODUTOS/ || /APIPRODUTOS{}" 

END WSRESTFUL

WSMETHOD GET WSRECEIVE id, tipo WSSERVICE APIPRODUTOS

    Local cJson := ""

    Local cQuerySB1     := ArrayToQuery(Self:aQueryString)
    Local aProdutos := {}

    aProdutos := GetProdutos(cQuerySB1)
    cJson := FWJsonSerialize(aProdutos)
   
    ::SetContentType("application/json")
    ::SetResponse(cJson)

Return (.T.)


Static Function ArrayToQuery(aQryString)
    
    Local cRet := ""    
    Local nCount  := 1
    Local cStatus := ""
    Local cFilterID      := ""
    Local cFilterTipo    := ""
    Local cFilterGrupo   := ""
    Local cFilterStatus  := ""
    Local cFilterDesc    := ""
    Local cSort          := ""
    Local cOrder         := ""
    Local cOffset        := ""
    Local cLimit         := ""

    for nCount := 1 to len(aQryString)

        // Cláusula B1_COD IN 
        If aQryString[nCount][CHAVE] == "ID"
            IF !Empty(AllTrim(UPPER( aQryString[nCount][VALOR])))
                cFilterID +=  "'%" + UPPER( aQryString[nCount][VALOR]) + "%', "
            ENDIF

        // Cláusula B1_TIPO IN 
        ELSEIF aQryString[nCount][CHAVE] == "TIPO"
            cFilterTipo += "'" + UPPER( aQryString[nCount][VALOR]) + "', "

        // Cláusula B1_GRUPO IN 
        ELSEIF aQryString[nCount][CHAVE] == "GRUPO"
            IF !Empty(AllTrim(UPPER( aQryString[nCount][VALOR])))
                cFilterGrupo += "'" + UPPER( aQryString[nCount][VALOR]) + "', "
            ENDIF

        // Cláusula B1_MSBLQL 
        ELSEIF aQryString[nCount][CHAVE] == "STATUS"
            cStatus := IIF(aQryString[nCount][VALOR] == "1", "2", "1")
            cFilterStatus += "'" + cStatus + "', "    
            
        ELSEIF aQryString[nCount][CHAVE] == "DESC"
            IF !Empty(AllTrim(UPPER( aQryString[nCount][VALOR])))
                cFilterDesc += "'%" + UPPER( aQryString[nCount][VALOR]) + "%', "
            ENDIF

        // Cláusula ORDER BY 
        ELSEIF aQryString[nCount][CHAVE] == "SORT"
            
            DO CASE 
                CASE UPPER(aQryString[nCount][VALOR]) == "ID"
                    cSort += " B1_COD, "
                CASE UPPER(aQryString[nCount][VALOR]) == "DESCRICAO"
                    cSort += " B1_DESC, "                    
                CASE UPPER(aQryString[nCount][VALOR]) == "TIPO"
                    cSort += " B1_TIPO, "
                CASE UPPER(aQryString[nCount][VALOR]) == "GRUPO"
                    cSort += " B1_GRUPO, "
                CASE UPPER(aQryString[nCount][VALOR]) == "STATUS"
                    cSort += " B1_MSBLQL, "
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
    cFilterID       := SubStr(cFilterID     , 1, Len(cFilterID      )  - 2) 
    cFilterTipo     := SubStr(cFilterTipo   , 1, Len(cFilterTipo    )  - 2) 
    cFilterGrupo    := SubStr(cFilterGrupo  , 1, Len(cFilterGrupo   )  - 2) 
    cFilterStatus   := SubStr(cFilterStatus , 1, Len(cFilterStatus  )  - 2) 
    cFilterDesc     := SubStr(cFilterDesc , 1, Len(cFilterDesc  )  - 2) 

    cFilterID       := StrTran( cFilterID, ";", "','" ) 
    cFilterTipo     := StrTran( cFilterTipo, ";", "','" ) 
    cFilterGrupo    := StrTran( cFilterGrupo, ";", "','" ) 
    cFilterStatus   := StrTran( cFilterStatus, ";", "','" ) 
    cFilterDesc     := StrTran( cFilterDesc, ";", "','" ) 
    
    IF !Empty(cSort)
        cSort      := Alltrim(SubStr(cSort , 1, Len(cSort          )  - 2) )
    else
        cSort      := "B1_COD"
    EndIf

    cRet :=        " SELECT *
    cRet += CRLF + " FROM   (SELECT ROW_NUMBER() "
    cRet += CRLF + "                  OVER( "
    cRet += CRLF + "                    ORDER BY SB1." + cSort + ") AS NUMBER, "
    cRet += CRLF + "                B1_COD, B1_DESC, B1_TIPO, B1_GRUPO, B1_MSBLQL "
    cRet += CRLF + "         FROM "         + RetSqlName("SB1")  + " SB1 "
    cRet += CRLF + "         WHERE  B1_FILIAL = '" + xFilial("SB1") + "'"

    If ! Empty(ALLTRIM(cFilterID))
        cRet += CRLF + "               AND B1_COD LIKE   (" + cFilterID  + ")" 
    EndIf  
    
    If ! Empty(ALLTRIM(cFilterTipo))
        cRet += CRLF + "               AND B1_TIPO IN  (" + cFilterTipo + ")"
    EndIf  
    If ! Empty(ALLTRIM(cFilterGrupo))
        cRet += CRLF + "               AND B1_GRUPO IN  (" + cFilterGrupo + ")"
    EndIf  
    If ! Empty(ALLTRIM(cFilterStatus))
        cRet += CRLF + "               AND B1_MSBLQL IN  (" + cFilterStatus + ")"
    EndIf  

    If ! Empty(ALLTRIM(cFilterDesc))
        cRet += CRLF + "               AND B1_DESC LIKE  (" +  cFilterDesc + ")"
    EndIf  

    cRet += CRLF +"                    AND SB1.D_E_L_E_T_ = '') AS TBL
    
    If ! Empty(ALLTRIM(cOffset))  .AND. ! Empty(ALLTRIM(cLimit))
        
        cRet += CRLF +" WHERE  NUMBER BETWEEN '"  + cOffset + "' AND '" + cLimit + "'
    
    ENDIF    
  
  cRet += CRLF +" ORDER  BY " + cSort + " " + cOrder + " ;"
    
    MemoWrite( SuperGetMV("BE_LOGDIR", , "\logbio\") + FunName() + "_ApiProdutos_QrySB1.sql", cRet)

Return cRet


Static Function GetProdutos(cQuerySB1)

    Local aArea     := GetArea()
    Local aRet      := {}

    If Select("QRYSB1") <> 0 
		QRYSB1->(DbCloseArea())
	EndIf

    TCQUERY (cQuerySB1) ALIAS "QRYSB1" NEW

	While  ! QRYSB1->( Eof() )

        aAdd( aRet, Produtos():New( QRYSB1->B1_COD ))
		QRYSB1->( dbSkip())

	EndDo
   
    RestArea(aArea)

Return aRet
