#Include 'Totvs.ch'
#Include 'Protheus.ch'

User Function ClassFuncionario()
Return

Class Funcionario

    Data cpf             As STRING
    Data matricula       As STRING
    Data nomeMae         As STRING

    Method New(cCpf) CONSTRUCTOR

EndClass

Method New(cCpf) Class Funcionario
    
    ::cpf           := ""
    ::matricula     := ""
    ::nomeMae       := ""
    
    DbSelectArea("SRA")
    SRA->(DbSetOrder(5))
        
    If SRA->( DbSeek( xFilial("SRA") + cCpf))
        ::cpf            := Alltrim(SRA->RA_CIC)
        ::matricula      := Alltrim(SRA->RA_MAT)
        ::nomeMae        := FwNoAccent(Alltrim(SRA->RA_MAE))
    EndIf
        
Return(Self)
