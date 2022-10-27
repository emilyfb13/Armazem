#INCLUDE 'PROTHEUS.CH'
#INCLUDE 'TOPCONN.CH'
#INCLUDE 'RESTFUL.CH'
#DEFINE CHAVE 1
#DEFINE VALOR 2
#DEFINE INSERIR 3
#DEFINE ALTERAR 4
#DEFINE CRLF chr(13) + chr(10)

/*Dummy Function
Serve apenas para reservar o nome do nosso '.prw' no RPO
Se necessário, pode ser consumida por um fonte de nosso projeto*/
User Function ApiLogin()
Return

// Declaração da estrutura do Web Service RestFul
WSRESTFUL ApiLogin DESCRIPTION "Serviço REST para manipulação de Solicitação ao Armazém"

    // Definição dos Atributos
    WSDATA user           AS STRING 
    WSDATA senha          AS STRING 
    
    // Definição do Método
   
   WSMETHOD GET     DESCRIPTION "Retorna verificação de acesso" WSSYNTAX "/ApiLogin/ || /ApiLogin{}" 
   WSMETHOD POST    DESCRIPTION "Inclui Novo Login"             WSSYNTAX "/ApiLogin" PRODUCES APPLICATION_JSON
   WSMETHOD PUT     DESCRIPTION "Alterar Senha do Login"        WSSYNTAX "/ApiLogin" PRODUCES APPLICATION_JSON

END WSRESTFUL 

WSMETHOD GET WSRECEIVE user, senha WSSERVICE APILOGIN

    Local cJson := ""

    Local cQueryLogin     := ArrayToQuery(Self:aQueryString)
    Local aLogin := {}

    aLogin := GetLogin(cQueryLogin)
    cJson := FWJsonSerialize(aLogin)
   
    ::SetContentType("application/json")
    ::SetResponse(cJson)

Return (.T.)

WSMETHOD POST WSSERVICE APILOGIN
    local jItens
    local cError    := ""
    local cJson     := ""
    local lOk       := .T.
    local aItens    := {}

    Self:SetContentType("application/json")

    jItens := JsonObject():New()
    cError := jItens:fromJson( self:getContent() )
    lOk := .F.

    if Empty(cError)

            aItens := {}
            aAdd(aItens, jItens["USER"])
            aAdd(aItens, jItens["SENHA"])
      
        cResponse := U_AuxiliarLogin(aItens, INSERIR)
        IF Type(cResponse) == "N"
            cJson := '{"USER":"'      + cValToChar(cResponse) + '"';
                    + ',"SUCESSO":"'+ "1"                   + '"';
                    + ',"OBS":"'    + "SUCESSO!"            + '"';
                    +'}'
            MemoWrite( SuperGetMV( "BE_LOGDIR", , "\logbio\") + FunName() + "ApiLogin_POST.json", cJson)
            ::SetResponse(cJson)
            lOk := .T.
        ELSE    
            cJson := '{"USER":"'      + "000000"             + '"';
                    + ',"SUCESSO":"' + "0"                  + '"';
                    + ',"OBS":"'     + cValToChar(cResponse)+ '"';
                    +'}'
            SetRestFault(400, cResponse)
            ::SetResponse(cJson)
        ENDIF
    EndIf
Return (lOk)

WSMETHOD PUT WSSERVICE APILOGIN
    local jItens
    local cError    := ""
    local cJson     := ""
    local lOk       := .T.
    local aItens    := {}
   

    Self:SetContentType("application/json")

    jItens := JsonObject():New()
    cError := jItens:fromJson( self:getContent() )
    lOk := .F.

    if Empty(cError)

            aItens := {}
            aAdd(aItens, jItens["USER"])
            aAdd(aItens, jItens["SENHA"])

        cResponse := U_AuxiliarLogin(aItens, ALTERAR)
        IF Type(cResponse) == "N"
            cJson := '{"USER":"'      + cValToChar(cResponse) + '"';
                    + ',"SUCESSO":"'+ "1"                   + '"';
                    + ',"OBS":"'    + "SUCESSO!"            + '"';
                    +'}'
            ::SetResponse(cJson)
            lOk := .T.
        ELSE    
            cJson := '{"USER":"'      + "000000"             + '"';
                    + ',"SUCESSO":"' + "0"                  + '"';
                    + ',"OBS":"'     + cValToChar(cResponse)+ '"';
                    +'}'
            SetRestFault(400, cResponse)
            ::SetResponse(cJson)
        ENDIF
    EndIf
Return (lOk)

Static Function ArrayToQuery(aQryString)
    
    Local cRet          := ""    
    Local nCount        := 1
    Local cFilterUser   := ""
    Local cFilterSenha  := ""

    for nCount := 1 to len(aQryString)

       
        If aQryString[nCount][CHAVE] == "USER"
            cFilterUser +=  "'" + ( aQryString[nCount][VALOR]) + "'"
        ENDIF

        If aQryString[nCount][CHAVE] == "SENHA"
            cFilterSenha +=  "'" + ( aQryString[nCount][VALOR]) + "'"
        ENDIF

    next nCount


    cRet :=        " SELECT ZZ_USER
    cRet += CRLF + "         FROM "         + RetSqlName("SZZ")  + ""
    cRet += CRLF + "           WHERE ZZ_USER = " + Alltrim(cFilterUser) +" "
    cRet += CRLF + "                AND ZZ_SENHA = " + Alltrim(cFilterSenha) +" "
    cRet += CRLF + "                AND ZZ_MSBLQL = '2' "
    cRet += CRLF + "                AND D_E_L_E_T_ = ' ' "

    
    MemoWrite( SuperGetMV("BE_LOGDIR", , "\logbio\") + FunName() + "_ApiLogin_QryLogin.sql", cRet)

Return cRet


Static Function GetLogin(cQueryLogin)

    Local aArea     := GetArea()
    Local aRet      := {}

    If Select("QRYLOGIN") <> 0 
		QRYLOGIN->(DbCloseArea())
	EndIf

    TCQUERY (cQueryLogin) ALIAS "QRYLOGIN" NEW

	While  ! QRYLOGIN->( Eof() )

        aAdd( aRet, Login():New( QRYLOGIN->ZZ_USER))
		QRYLOGIN->( dbSkip())

	EndDo
   
    RestArea(aArea)

Return aRet
