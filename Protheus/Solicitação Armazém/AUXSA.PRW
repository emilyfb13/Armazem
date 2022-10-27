#INCLUDE 'PROTHEUS.CH'
#INCLUDE 'TOPCONN.CH'
#DEFINE INSERIR 3
#DEFINE ALTERAR 4
#DEFINE EXCLUIR 5

#DEFINE CRLF CHR(13) + CHR(10)

User Function AUXSA( aCampo, nOpcx, cID)
    
   	Local aCab     	:= {}
	Local aItens    := {}
	Local aError
	Local aArea   	:= GetArea()
	Local nCount    := 1
	Local cRet		:= "1"
	Local nSaveSx8	:= 0
	Local cNumero	:= ''

	Private lMsErroAuto := .F.
	Private lMsErroHelp := .T.

	IF Empty(aCampo)

		Return ""

	ENDIF

	nSaveSx8:= GetSx8Len()

	dbSelectArea( 'SCP' )
	SCP->( dbSetOrder( 1 ) )

	If nOpcx == 3
		While SCP->( dbSeek( xFilial( 'SCP' ) + cNumero ) )
			ConfirmSx8()
			cNumero := GetSx8Num('SCP', 'CP_NUM')
		EndDo
	EndIf

	//Cabeçalho
	If nOpcx == 3
		aCab:={	{"CP_NUM" 		, cNumero   	  	    , Nil},;
				{"CP_EMISSAO"	, dDataBase  	  	    , Nil}} // Data de Emissao

		For nCount  := 1 to len(aCampo)

			dbSelectArea("SB1")
			SB1->( dbSetOrder( 1 ) )
			SCP->( dbSetOrder( 1 ) )
				
			If MsSeek(xFilial("SB1") + aCampo[nCount][2])
				// Itens
				
				Aadd(aItens,;
					{{"CP_XXMATR"  , cValToChar(StrZero(VAL(aCampo[nCount][1]), 5))			, Nil},; //Matricula Solicitante
					{"CP_ITEM"		, StrZero(nCount, 2)									, Nil},; //Numero do Item
					{"CP_PRODUTO"   , SB1->B1_COD		    								, Nil},; //Codigo do Produto
					{"CP_QUANT"  	, aCampo[nCount][3]	   									, Nil},; //Quantidade
					{"CP_LOCAL"  	, SB1->B1_LOCPAD										, Nil},; //Armazem
					{"CP_UM"  		, SB1->B1_UM											, Nil},; //Unidade de medida
					{"CP_CC"  		, aCampo[nCount][4]  									, Nil},; //Centro de Custo
					{"CP_OBS"  		, aCampo[nCount][5]										, Nil},; //Observacao
					{"CP_DATPRF"	, dDataBase												, Nil}}) 
			Endif
		Next nCount
		
		MSExecAuto({|v,x,y| MATA105(v,x,y)},aCab,aItens,nOpcx)
		
		// Mostra Erro na geracao de Rotinas automaticas
		If lMsErroAuto
			If !__lSX8
				RollBackSx8()
			EndIf
			aError 	:= GetAutoGRLog()
			cRet 	:= GetError( aError, aCab, aItens)	
		// Tudo certo!
		Else	
			While ( GetSx8Len() > nSaveSx8 )
				ConfirmSx8()
			End
			cRet := cNumero
		Endif
	ENDIF

	IF nOpcx == 4
		aCab:={	{"CP_NUM" 		, cID   	  	    , Nil},;
				{"CP_EMISSAO"	, dDataBase  	  	, Nil}} // Data de Emissao

		For nCount  := 1 to len(aCampo)

			dbSelectArea("SB1")
			SB1->( dbSetOrder( 1 ) )
			SCP->( dbSetOrder( 1 ) )
			
			If MsSeek(xFilial("SB1") + aCampo[nCount][3])
				// Itens
				Aadd(aItens,;
					{{"CP_XXMATR"  , cValToChar(StrZero(VAL(aCampo[nCount][1]), 6))	, Nil},; //Matricula Solicitante
					{"CP_ITEM"		, aCampo[nCount][2]   								, Nil},; //Numero do Item
					{"CP_PRODUTO"   , SB1->B1_COD	 									, Nil},; //Codigo do Produto
					{"CP_DESCRI"    , SB1->B1_DESC		 								, Nil},; //Descrição do Produto
					{"CP_QUANT"  	, aCampo[nCount][4]							  		, Nil},; //Quantidade
					{"CP_LOCAL"  	, SB1->B1_LOCPAD									, Nil},; //Armazem
					{"CP_UM"  		, SB1->B1_UM										, Nil},; //Unidade de medida
					{"CP_CC"  		, aCampo[nCount][5]  								, Nil},; //Centro de Custo
					{"CP_OBS"  		, aCampo[nCount][6]									, Nil},; //Observacao
					{"CP_DATPRF"	, dDataBase											, Nil}}) 
			Endif
		Next nCount

		 // Solicitação ao Armazém
		MSExecAuto({|v,x,y| MATA105(v,x,y)},aCab,aItens,nOpcx)
		
		// Mostra Erro na geracao de Rotinas automaticas
		If lMsErroAuto
			aError 	:= GetAutoGRLog()
			cRet 	:= GetError( aError, aCab, aItens)
		// Tudo certo!
		Else
			cRet := cID
		Endif
	ENDIF

	IF nOpcx == 5
	
		For nCount  := 1 to len(aCampo)

			dbSelectArea("SCP")
			SCP->( dbSetOrder( 1 ) )

			If MsSeek(xFilial("SCP") + cID + aCampo[nCount][1]) 
				
				aCab:={	{"CP_NUM" 		, cID   	  	    , Nil},; 
						{"CP_EMISSAO"	, SCP->CP_EMISSAO  	, Nil}} // Data de Emissao
				// Itens
				aItens := {}
				Aadd(aItens,;
					{{"CP_ITEM"		,SCP->CP_ITEM  		  ,Nil}}) //Numero do Item
				// Solicitação ao Armazém
				MSExecAuto({|v,x,y| MATA105(v,x,y)},aCab,aItens,nOpcx)
			Endif
			
		Next nCount
		
		// Mostra Erro na geracao de Rotinas automaticas
		If lMsErroAuto
			
			aError 	:= GetAutoGRLog()
			cRet 	:= GetError( aError, aCab, aItens)
		// Tudo certo!
		Else
			cRet := cID
		Endif
	ENDIF    
	RestArea(aArea)

Return cRet


Static Function GetError( aError, aCab, aItens)
	
	Local cRet := "BIOEST02 - Falha ao executar MsExecAuto da rotina MATA105! " + Time() + CRLF

	Local nLog, nXItens, nYItens
	Local cPara, cAssunto, cMsg

	IF ! Empty( aError)
		
		for nLog := 1 to Len(aError)
			cRet += aError[nLog] + "<br>"
		next nLog
	ENDIF	

	cPara	:= GetMV("MV_XXEST14")
	cAssunto:= "[PROTHEUS] BIOEST02 - SOLICITACAO AO ARMAZEM  | " +  GetEnvServer()
	
	cMsg	:= "Ocorreu um problema na rotina automática MATA105 e não foi possível concluir a operação." + CRLF
	cMsg	+= " Verifique o CONSOLELOG do ambiente <b>" +  GetEnvServer() + "</b>." + CRLF + CRLF
	cMsg 	+= "<br><b>Mensagem retornada pela aplicação:</b><br>" 
	cMsg 	+=  cRet
	
	cMsg	+= "<br><b>O que enviamos para o MsExecAuto:</b><br> " 
	cMsg	+= "Cabeçalho:"
	cMsg	+= "<ul>"
	
	for nLog = 1 to len(aCab)
		cMsg += "<li>" + aCab[nLog][1] + " | " + cValToChar( aCab[nLog][2]) + "</li>"
	next nLog
	
	cMsg	+= "</ul>"
	cMsg	+= "Itens:"
	cMsg	+= "<ul>"		

	// Imprime o array do MsExecAuto
	For nXItens := 1 to Len(aItens)
		
		for nYItens := 1 to Len(aItens[nXItens])
			cMsg += " <li>| "	+ cValToChar( aItens[nxItens][nyItens][1]) +;
						" | "	+ cValToChar( aItens[nxItens][nyItens][2]) + "</li>"
		next nYItens
		
	next nXItens
	cMsg	+= "</ul>"

	U_BIOPADEM( cPara, cAssunto ,cMsg, "", , )
	FWLogMsg("INFO", , "SOAP", AllTrim(FunName()), "", "01", "BIOEST02: " + Time() + cMsg, 0, 0, {})

Return cRet
