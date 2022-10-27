#INCLUDE 'PROTHEUS.CH'
#INCLUDE 'TOPCONN.CH'
#INCLUDE 'RESTFUL.CH'
#DEFINE CHAVE 1
#DEFINE VALOR 2
#DEFINE INSERIR 3
#DEFINE ALTERAR 4
#DEFINE EXCLUIR 5
#DEFINE CRLF chr(13) + chr(10)

/*Dummy Function
Serve apenas para reservar o nome do nosso '.prw' no RPO
Se necessário, pode ser consumida por um fonte de nosso projeto*/
User Function ApiSolicitacaoArmazem()
Return

// Declaração da estrutura do Web Service RestFul
WSRESTFUL ApiSolicitacaoArmazem DESCRIPTION "Serviço REST para manipulação de Solicitação ao Armazém"

    // Definição dos Atributos
    WSDATA id           AS STRING 
    WSDATA solicitante  AS STRING 
    
    // Definição do Método
   
   WSMETHOD GET     DESCRIPTION "Retorna as Solicitações ao Armazém" WSSYNTAX "/ApiSolicitacaoArmazem/ || /ApiSolicitacaoArmazem{}" 
   WSMETHOD POST    DESCRIPTION "Inclui Solicitações ao Armazém" WSSYNTAX "/ApiSolicitacaoArmazem" PRODUCES APPLICATION_JSON
   WSMETHOD PUT  DESCRIPTION "Alterar as Solicitações ao Armazém" WSSYNTAX "/ApiSolicitacaoArmazem" PRODUCES APPLICATION_JSON
   WSMETHOD DELETE  DESCRIPTION "Deletar as Solicitações ao Armazém" WSSYNTAX "/ApiSolicitacaoArmazem" PRODUCES APPLICATION_JSON

END WSRESTFUL 

WSMETHOD GET WSRECEIVE id, solicitante WSSERVICE ApiSolicitacaoArmazem

    Local cJson := ""

    Local cQuerySCP     := ArrayToQuery( Self:aQueryString)
    Local aSolicitacoes := {}

    aSolicitacoes := GetSolicitacaoArmazem( cQuerySCP)
    cJson := FWJsonSerialize( aSolicitacoes)
   
    // Grava no arquivo JSON
    MemoWrite( SuperGetMV( "BE_LOGDIR", , "\logbio\") + FunName() + "ApiSolicArmazem_GET.json", cJson)

    ::SetContentType("application/json")
    ::SetResponse( cJson)

Return (.T.)

WSMETHOD POST WSSERVICE ApiSolicitacaoArmazem
    local jSolicitacao
    local cError    := ""
    local cJson     := ""
    local lOk       := .T.
    local aItens    := {}
    Local aSolicit  := {}
    Local nItens    := 0
    Local nCount    := 1

    Self:SetContentType("application/json")

    jSolicitacao := JsonObject():New()
    cError := jSolicitacao:fromJson( self:getContent() )
    lOk := .F.

    if Empty(cError)
        nItens := Len(jSolicitacao["ITENS"])

        for nCount := 1 to nItens
            aItens := {}
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["SOLICITANTE"])
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["PRODUTO"])
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["QUANTIDADE"])
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["CENTROCUSTO"])
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["OBSERVACAO"])
            aAdd(aSolicit, aItens)
        Next nCount

        cResponse := AUXSA(aSolicit, INSERIR)
        IF Type(cResponse) == "N"
            cJson := '{"ID":"'      + cValToChar(cResponse) + '"';
                    + ',"SUCESSO":"'+ "1"                   + '"';
                    + ',"OBS":"'    + "SUCESSO!"            + '"';
                    +'}'
            ::SetResponse(cJson)
            lOk := .T.
        ELSE    
            cJson := '{"ID":"'       + "000000"             + '"';
                    + ',"SUCESSO":"' + "0"                  + '"';
                    + ',"OBS":"'     + cValToChar(cResponse)+ '"';
                    +'}'
            SetRestFault(400, cResponse)
            ::SetResponse(cJson)
        ENDIF
    EndIf
Return (lOk)

WSMETHOD PUT WSSERVICE ApiSolicitacaoArmazem 
    local jSolicitacao
    local cError    := ""
    local cJson     := ""
    local lOk       := .T.
    local aItens    := {}
    Local aSolicit  := {}
    Local nItens    := 0
    Local nCount    := 1

    Self:SetContentType("application/json")

    jSolicitacao := JsonObject():New()
    cError := jSolicitacao:fromJson( self:getContent() )
    lOk := .F.

    if Empty(cError)
        nItens := Len(jSolicitacao["ITENS"])

        for nCount := 1 to nItens
            aItens := {}
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["SOLICITANTE"])
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["ITEM"])
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["PRODUTO"])
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["QUANTIDADE"])
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["CENTROCUSTO"])
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["OBSERVACAO"])
            aAdd(aSolicit, aItens)
        Next nCount

        cResponse := AUXSA(aSolicit, ALTERAR, jSolicitacao["ID"])
        IF Type(cResponse) == "N"
            cJson := '{"ID":"'      + cValToChar(cResponse) + '"';
                    + ',"SUCESSO":"'+ "1"                   + '"';
                    + ',"OBS":"'    + "SUCESSO!"            + '"';
                    +'}'
            ::SetResponse(cJson)
            lOk := .T.
        ELSE    
            cJson := '{"ID":"'       + "000000"             + '"';
                    + ',"SUCESSO":"' + "0"                  + '"';
                    + ',"OBS":"'     + cValToChar(cResponse)+ '"';
                    +'}'
            SetRestFault(400, cResponse)
            ::SetResponse(cJson)
        ENDIF
    EndIf
Return (lOk)

WSMETHOD DELETE WSSERVICE ApiSolicitacaoArmazem 
    local jSolicitacao
    local cError    := ""
    local cJson     := ""
    local lOk       := .T.
    local aItens    := {}
    Local aSolicit  := {}
    Local nItens    := 0
    Local nCount    := 1

    Self:SetContentType("application/json")

    jSolicitacao := JsonObject():New()
    cError := jSolicitacao:fromJson( self:getContent() )
    lOk := .F.

    if Empty(cError)
        nItens := Len(jSolicitacao["ITENS"])

        for nCount := 1 to nItens
            aItens := {}
            aAdd(aItens, jSolicitacao["ITENS"][nCount]["ITEM"])
            aAdd(aSolicit, aItens)
        Next nCount

        cResponse := AUXSA(aSolicit, EXCLUIR, jSolicitacao["ID"])
        IF Type(cResponse) == "N"
            cJson := '{"ID":"'      + cValToChar(cResponse) + '"';
                    + ',"SUCESSO":"'+ "1"                   + '"';
                    + ',"OBS":"'    + "SUCESSO!"            + '"';
                    +'}'
            ::SetResponse(cJson)
            lOk := .T.
        ELSE    
            cJson := '{"ID":"'       + "000000"             + '"';
                    + ',"SUCESSO":"' + "0"                  + '"';
                    + ',"OBS":"'     + cValToChar(cResponse)+ '"';
                    +'}'
            SetRestFault(400, cResponse)
            ::SetResponse(cJson)
        ENDIF
    EndIf
Return (lOk)

Static Function ArrayToQuery( aQryString)
    
    Local cRet         := ""    
    Local nCount       := 1
    Local cFilterID    := ""
    Local cFilterSolic := ""
    Local cSort        := ""
    Local cDash1       := ""
    Local cDash2       := ""
    Local cOrder       := ""
    Local cOffset      := ""
    Local cLimit       := ""
    Local cCodProd     := ""
    Local cProd        := ""
    Local cObs         := ""
    Local cEmissaoIni  := ""
    Local cEmissaoFim  := ""

    for nCount := 1 to len( aQryString)

        // Cláusula CP_NUM IN 
        If aQryString[nCount][CHAVE] == "ID"
            IF !Empty(AllTrim(UPPER( aQryString[nCount][VALOR])))
                cFilterID +=  "'" + UPPER( aQryString[nCount][VALOR]) + "', "
            ENDIF
       
         // Cláusula CP_XXMATR IN 
        ELSEIF aQryString[nCount][CHAVE] == "SOLICITANTE"
            cFilterSolic += "'" + CValToChar(StrZero(VAL(aQryString[nCount][VALOR]),6)) + "', "

        // Cláusula ORDER BY 
        ELSEIF aQryString[nCount][CHAVE] == "SORT"
            
            DO CASE 
                CASE UPPER(aQryString[nCount][VALOR]) == "ID"
                    cSort += " CP_NUM, "               
            END
        
        ELSEIF aQryString[nCount][CHAVE] == "DASH"
            DO CASE
                 CASE UPPER(aQryString[nCount][VALOR]) == "FIM"
                    cDash2 += "AND SCP.CP_STATUS <> '' AND SCP.CP_QUANT >= SCP.CP_QUJE  AND SCP.CP_PREREQU = 'S' "
                CASE UPPER(aQryString[nCount][VALOR]) == "INCLUIDO"
                    cDash2 += "AND SCP.CP_STATUS = '' AND SCP.CP_PREREQU = '' "
                CASE UPPER(aQryString[nCount][VALOR]) == "PC"
                    cDash1 +=  " INNER JOIN " + RetSqlName("SCQ") + " SCQ ON SCP.CP_NUM = SCQ.CQ_NUM AND SCP.CP_ITEM = SCQ.CQ_ITEM "
                    cDash2 += " AND SCP.CP_STATUS = '' AND SCP.CP_PREREQU = 'S' AND SCQ.CQ_NUMSC <> '' AND SCP.CP_QUANT > SCP.CP_QUJE "
                CASE UPPER(aQryString[nCount][VALOR]) == "DISP"
                    cDash1 +=  " INNER JOIN " + RetSqlName("SCQ") + " SCQ ON SCP.CP_NUM = SCQ.CQ_NUM AND SCP.CP_ITEM = SCQ.CQ_ITEM "
                    cDash2 += " AND SCP.CP_STATUS = '' AND SCP.CP_PREREQU = 'S' AND SCP.CP_QUANT > SCP.CP_QUJE AND SCQ.CQ_QTDISP > '0' AND SCQ.CQ_NUMREQ = ''"
            END

        // Cláusula ORDER BY 
        ELSEIF aQryString[nCount][CHAVE] == "ORDER"
            cOrder := UPPER( aQryString[nCount][VALOR]) 

         // Cláusula RANGE INÍCIO DA PAGINAÇÃO
        ELSEIF aQryString[nCount][CHAVE] == "OFFSET"
            cOffset     =+ UPPER( aQryString[nCount][VALOR])

        //Cláusula RANGE LIMITE DA PAGINAÇÃO
        ELSEIF aQryString[nCount][CHAVE] == "LIMIT"  
            cLimit       =+ UPPER( aQryString[nCount][VALOR]) 
        
        ELSEIF aQryString[nCount][CHAVE] == "CODPROD"  
            IF !Empty(AllTrim(UPPER( aQryString[nCount][VALOR])))
                cCodProd    =+ "'%" + UPPER( aQryString[nCount][VALOR]) + "%'"
            ENDIF

        ELSEIF aQryString[nCount][CHAVE] == "PROD"  
            IF !Empty(AllTrim(UPPER( aQryString[nCount][VALOR])))
                cProd    =+ "'%" + UPPER( aQryString[nCount][VALOR]) + "%'"
            ENDIF

        ELSEIF aQryString[nCount][CHAVE] == "OBS"  
            IF !Empty(AllTrim(UPPER( aQryString[nCount][VALOR])))
                cObs    =+ "'%" + UPPER( aQryString[nCount][VALOR]) + "%'"
            ENDIF
        
        ELSEIF aQryString[nCount][CHAVE] == "EMISSAOINI"  
            IF !Empty(AllTrim(UPPER( aQryString[nCount][VALOR])))
                cEmissaoIni =+ (UPPER( aQryString[nCount][VALOR]))
            ENDIF 

        ELSEIF aQryString[nCount][CHAVE] == "EMISSAOFIM"  
            IF !Empty(AllTrim(UPPER( aQryString[nCount][VALOR])))
                cEmissaoFim =+ (UPPER( aQryString[nCount][VALOR]))
            ENDIF

        EndIf
    next nCount

    // Retira a última vírgula 
    cFilterID       := SubStr( cFilterID     , 1, Len(cFilterID      ) - 2) 
    cFilterSolic    := SubStr( cFilterSolic  , 1, Len(cFilterSolic   ) - 2) 

    cFilterID       := StrTran( cFilterID, ";", "','" ) 
    cFilterSolic    := StrTran( cFilterSolic, ";", "','" ) 

    IF !Empty(ALLTRIM(cSort))
        cSort := Alltrim( SubStr( cSort , 1, Len( cSort) - 2) )
    Else
        cSort := " CP_NUM"
    EndIf

    cRet :=        " SELECT *
    cRet += CRLF + " FROM   ( SELECT ROW_NUMBER() "
    cRet += CRLF + "         OVER( "
    cRet += CRLF + "         ORDER BY SCP." + cSort + ") AS NUMBER, "
    cRet += CRLF + "                CP_NUM"
    cRet += CRLF + "         FROM "         + RetSqlName("SCP")  + " SCP "

    //INNER JOIN SCQ
    If ! Empty(ALLTRIM(cDash1))
        cRet += cDash1
    EndIf

    cRet += CRLF + "         WHERE  CP_FILIAL = '" + xFilial("SCP") + "'"
    
    If ! Empty(ALLTRIM(cFilterID))
        cRet += "   AND CP_NUM IN   (" + cFilterID  + ")"
    EndIf
    If ! Empty(ALLTRIM(cFilterSolic))
        cRet += "   AND CP_XXMATR IN  (" + cFilterSolic + ")"
    EndIf
    //Dashboard
    If ! Empty(ALLTRIM(cDash2))
        cRet += cDash2
    EndIf
    
    If ! Empty(ALLTRIM(cCodProd))
        cRet += "   AND CP_PRODUTO LIKE  (" + cCodProd + ")"
    EndIf

    If ! Empty(ALLTRIM(cProd))
        cRet += "   AND CP_DESCRI LIKE  (" + cProd + ")"
    EndIf

    If ! Empty(ALLTRIM(cObs))
        cRet += "   AND CP_OBS LIKE  (" + cObs + ")"
    EndIf

    If ! Empty(ALLTRIM(cEmissaoIni)) .AND. ! Empty(ALLTRIM(cEmissaoFim))
        cRet += "   AND CP_EMISSAO BETWEEN  '" + cEmissaoIni + "' AND '" + cEmissaoFim + "'    
    EndIf
    
    cRet += CRLF + "                    AND SCP.D_E_L_E_T_ = ''
    cRet += CRLF + "          GROUP BY CP_NUM ) AS TBL "
    
    // Paginação.
    If ! Empty(cOffset)  .AND. ! Empty(cLimit)    
        cRet += CRLF +" WHERE  NUMBER BETWEEN '"  + cOffset + "' AND '" + cLimit + "'    
    ENDIF    
  
    cRet += CRLF +" ORDER  BY " + cSort + " " + cOrder + " ;"

    // Grava no arquivo a query
    MemoWrite( SuperGetMV( "BE_LOGDIR", , "\logbio\") + FunName() + "ApiSolicArmazem_QrySCP.sql", cRet)

Return cRet

/*
** Retorna um array de objetos Solicitação ao Armazém. 
*/
Static Function GetSolicitacaoArmazem( cQuerySCP)

    Local aArea := GetArea()
    Local aRet  := {}

    If Select("QRYSCP") <> 0 
		QRYSCP->(DbCloseArea())
	EndIf

    TCQUERY ( cQuerySCP) ALIAS "QRYSCP" NEW

	While  ! QRYSCP->( Eof() )

        aAdd( aRet, SolicitacaoArmazem():New( QRYSCP->CP_NUM ))
		QRYSCP->( dbSkip())

	EndDo
   
    RestArea( aArea)

Return aRet
