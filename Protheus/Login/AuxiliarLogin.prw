#INCLUDE 'PROTHEUS.CH'
#INCLUDE 'TOPCONN.CH'
#DEFINE INSERIR 3
#DEFINE ALTERAR 4

#DEFINE CRLF CHR(13) + CHR(10)

User Function AuxiliarLogin( aCampo, nOpcx)
    
	Local aArea   	:= GetArea()
	Local cRet		:= "1"

    Local cUser          := ""
    Local cNome          := ""
    Local cMatricula     := ""
    Local cSenhaCript    := ""

	IF Empty(aCampo)

		Return ""

	ENDIF


	dbSelectArea( 'SZZ' )
	SZZ->( dbSetOrder( 1 ) )

	//INSERIR
	If nOpcx == 3

        dbSelectArea("SZZ")
		SZZ->( dbSetOrder( 1 ) )
				
		IF MsSeek(xFilial("SZZ") + aCampo[1])
             cRet := "Usuário já existente, favor recuperar senha."
        ELSE

			dbSelectArea("SRA")
			SRA->( dbSetOrder( 5 ) )
				
			If MsSeek(xFilial("SRA") + aCampo[1])
				cUser          := aCampo[1]
                cNome          := Alltrim(SRA->RA_NOME)
                cMatricula     := Alltrim(SRA->RA_MAT)
                cSenhaCript    := aCampo[2]
			Endif
		


            Begin Transaction
            
                //Monta o Update
                cQryInsert := " INSERT INTO " + RetSqlName("SZZ") + " "
                cQryInsert += "    (ZZ_FILIAL, ZZ_USER, ZZ_NOME, ZZ_MAT, ZZ_SENHA, ZZ_MSBLQL, R_E_C_N_O_)"
                cQryInsert += " VALUES ( "
                cQryInsert += "     '01', "
                cQryInsert += "     '" + cUser + "', "
                cQryInsert += "     '" + cNome + "', "
                cQryInsert += "     '" + cMatricula + "', "
                cQryInsert += "     '" + cSenhaCript + "', "
                cQryInsert += "     '2' , "
                cQryInsert += "      (SELECT MAX(R_E_C_N_O_) + 1 FROM  " + RetSqlName("SZZ") + ")     ); "

                MemoWrite( SuperGetMV("BE_LOGDIR", , "\logbio\") + FunName() + "_ApiLogin_QryFuncionarioPost.sql", cQryInsert)
            
                //Tenta executar o update
                nErro := TcSqlExec(cQryInsert)
                
                //Se houve erro, mostra a mensagem e cancela a transação
                If nErro != 0
                    cRet := "Erro na execução da Insert: " +TcSqlError() + "."
                    DisarmTransaction()
                Else
                    cRet := cUser
                EndIf
            End Transaction

            // Executa a atualização do controle de memória do Recno no Topconnect
            TcRefresh(RetSqlName("SZZ"))
        ENDIF
	ENDIF

    //ALTERAR
	IF nOpcx == 4

			cUser          := aCampo[1]
            cSenhaCript    := aCampo[2]
			
        Begin Transaction
            
            //Monta o Update
            cQryUpd := " UPDATE " + RetSqlName("SZZ") + " "
            cQryUpd += "     SET ZZ_USER = '"  + cUser + "' , "
            cQryUpd += "         ZZ_SENHA = '" + cSenhaCript + "' "
            cQryUpd += " WHERE "
            cQryUpd += "     ZZ_USER     = '" + cUser + "' "
            cQryUpd += "     AND ZZ_MSBLQL = '2' "
            cQryUpd += "     AND D_E_L_E_T_ = ' ' "
        
            //Tenta executar o update
            nErro := TcSqlExec(cQryUpd)
            
            //Se houve erro, mostra a mensagem e cancela a transação
            If nErro != 0
                cRet := "Erro na execução da Update: " +TcSqlError() + "."
                DisarmTransaction()
            Else
                cRet := cUser
            EndIf
        End Transaction

        // Executa a atualização do controle de memória do Recno no Topconnect
        TcRefresh(RetSqlName("SZZ"))
        
	ENDIF
	
	RestArea(aArea)

Return cRet


