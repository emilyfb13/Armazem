#INCLUDE 'PROTHEUS.CH'
#INCLUDE 'TOPCONN.CH'
#INCLUDE 'RESTFUL.CH'
#DEFINE CRLF chr(13) + chr(10)
#DEFINE CHAVE 1
#DEFINE VALOR 2

/*Dummy Function
Serve apenas para reservar o nome do nosso '.prw' no RPO
Se necessário, pode ser consumida por um fonte de nosso projeto*/
User Function ApiFuncionario()
Return

// Declaração da estrutura do Web Service RestFul
WSRESTFUL ApiFuncionario      DESCRIPTION "Serviço REST para manipulação de Funcionarios"

    // Definição dos Atributos
    WSDATA cpf   AS STRING 
    
    // Definição do Método
   
   WSMETHOD GET DESCRIPTION "Retorna os dados do funcionario pelo CPF" WSSYNTAX "/APIFUNCIONARIO/ || /APIFUNCIONARIO{}" 

END WSRESTFUL

WSMETHOD GET WSRECEIVE CPF WSSERVICE APIFUNCIONARIO

    Local cJson := ""

    Local cQueryFunc    := ArrayToQuery(Self:aQueryString)
    Local aFuncionario := {}

    aFuncionario := GetFuncionario(cQueryFunc)
    cJson := FWJsonSerialize(aFuncionario)
   
    ::SetContentType("application/json")
    ::SetResponse(cJson)

Return (.T.)

Static Function ArrayToQuery(aQryString)
    
    Local cRet          := ""    
    Local nCount        := 1
    Local cFilterCPF   := ""
    Local cFilterMat   := ""
    Local cFilterMae   := ""

    for nCount := 1 to len(aQryString)

       
        If aQryString[nCount][CHAVE] == "CPF"
            cFilterCPF +=  "'" + UPPER( aQryString[nCount][VALOR]) + "'"
        ENDIF
        If aQryString[nCount][CHAVE] == "MATRICULA"
            cFilterMat +=  "'" + UPPER( aQryString[nCount][VALOR]) + "'"
        ENDIF
        If aQryString[nCount][CHAVE] == "MAE"
            cFilterMae +=  "'" + UPPER( aQryString[nCount][VALOR]) + "'"
        ENDIF

    next nCount

    cRet :=        " SELECT RA_CIC
    cRet += CRLF + "         FROM "         + RetSqlName("SRA")  + ""
    cRet += CRLF + "           WHERE RA_CIC = " + Alltrim(cFilterCPF) +" "
    cRet += CRLF + "                AND RA_MAT = " + Alltrim(cFilterMat) +" "
    cRet += CRLF + "                AND RA_MAE = " + Alltrim(cFilterMae) +" "
    cRet += CRLF + "                AND RA_MSBLQL = '2' "
    cRet += CRLF + "                AND D_E_L_E_T_ = ' ' "
    
    MemoWrite( SuperGetMV("BE_LOGDIR", , "\logbio\") + FunName() + "_ApiLogin_QryFuncionario.sql", cRet)

Return cRet


Static Function GetFuncionario(cQueryFunc)

    Local aArea     := GetArea()
    Local aRet      := {}

    If Select("QRYFUNC") <> 0 
		QRYFUNC->(DbCloseArea())
	EndIf

    TCQUERY (cQueryFunc) ALIAS "QRYFUNC" NEW

	While  ! QRYFUNC->( Eof() )

        aAdd( aRet, Funcionario():New( QRYFUNC->RA_CIC))
		QRYFUNC->( dbSkip())

	EndDo
   
    RestArea(aArea)

Return aRet
