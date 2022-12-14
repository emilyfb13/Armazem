#INCLUDE 'PROTHEUS.CH'
#INCLUDE 'TOPCONN.CH'
#INCLUDE 'RWMAKE.CH'

User Function ESTSZZ()

	Local cFunExc	:= "U_DelUser()"
	Local cFunAlt	:= "U_UpdUser()"
	
	ChkFile('SZZ')
	dbSelectArea("SZZ")
    dbSetOrder(1) 
	// Monta a tela padr?o (mBrowse - Modelo 1).
	AxCadastro("SZZ", "Cadastro de Usu?rios Armaz?m Web", cFunExc, cFunAlt)	
	
Return ( .T. )


User Function DelUser()

	MsgInfo("N?o ? poss?vel excluir o Usu?rio. Clique em alterar e utilize a op??o Bloqueio.", "Aten??o!")

Return( .F. )

User Function UpdUser()

	Local lRet 	:= .F.
	Local cMsg 	:= "ATEN??O: Confirma a " + IIF(INCLUI, "inclus?o", "altera??o")+ " deste registro? "

	DbSelectArea("SZZ")
	SZZ->( dbSetOrder(1))
	IF INCLUI
		IF	SZZ->(DbSeek(xFILIAL("SZZ")+M->ZZ_USER))
			MsgStop("J? existe um Usu?rio com este CPF", "Aten??o!")
			Return lRet
		ENDIF
	ENDIF
	IF M->ZZ_MSBLQL == '1' .AND. Empty( M->ZZ_DTFIM)
		MsgStop("? obrigat?rio informar a <b>Data Fim</b> de atividade para Usu?rios bloqueados!", "Aten??o!")
	
	ELSEIF M->ZZ_MSBLQL <> '1' .AND. ! Empty( M->ZZ_DTFIM)
		MsgStop("A <b>Data Fim</b> de atividade n?o dever? ser preenchida para Usu?rios ativos!", "Aten??o!")
	
	ELSE 	
	 	lRet := MsgBox(cMsg, "Confirma??o", "YESNO")
	ENDIF

Return lRet
