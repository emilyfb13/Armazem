#INCLUDE 'PROTHEUS.CH'
#INCLUDE 'TOPCONN.CH'
#INCLUDE 'RWMAKE.CH'

User Function ESTSZZ()

	Local cFunExc	:= "U_DelUser()"
	Local cFunAlt	:= "U_UpdUser()"
	
	ChkFile('SZZ')
	dbSelectArea("SZZ")
    dbSetOrder(1) 
	// Monta a tela padrão (mBrowse - Modelo 1).
	AxCadastro("SZZ", "Cadastro de Usuários Armazém Web", cFunExc, cFunAlt)	
	
Return ( .T. )


User Function DelUser()

	MsgInfo("Não é possível excluir o Usuário. Clique em alterar e utilize a opção Bloqueio.", "Atenção!")

Return( .F. )

User Function UpdUser()

	Local lRet 	:= .F.
	Local cMsg 	:= "ATENÇÃO: Confirma a " + IIF(INCLUI, "inclusão", "alteração")+ " deste registro? "

	DbSelectArea("SZZ")
	SZZ->( dbSetOrder(1))
	IF INCLUI
		IF	SZZ->(DbSeek(xFILIAL("SZZ")+M->ZZ_USER))
			MsgStop("Já existe um Usuário com este CPF", "Atenção!")
			Return lRet
		ENDIF
	ENDIF
	IF M->ZZ_MSBLQL == '1' .AND. Empty( M->ZZ_DTFIM)
		MsgStop("É obrigatório informar a <b>Data Fim</b> de atividade para Usuários bloqueados!", "Atenção!")
	
	ELSEIF M->ZZ_MSBLQL <> '1' .AND. ! Empty( M->ZZ_DTFIM)
		MsgStop("A <b>Data Fim</b> de atividade não deverá ser preenchida para Usuários ativos!", "Atenção!")
	
	ELSE 	
	 	lRet := MsgBox(cMsg, "Confirmação", "YESNO")
	ENDIF

Return lRet
