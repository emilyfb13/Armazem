#Include 'Totvs.ch'
#Include 'Protheus.ch'

User Function ClassLogin()
Return

Class Login

    Data nome           As STRING
    Data matricula      As STRING

    Method New(cUser) CONSTRUCTOR

EndClass

Method New (cUser) Class Login
    
    ::nome           := ""
    ::matricula      := ""
    
    DbSelectArea("SZZ")
    SZZ->(DbSetOrder(1))
        
    If SZZ->( DbSeek( xFilial("SZZ") + cUser))
        ::nome            := Alltrim(SZZ->ZZ_NOME)
        ::matricula     := FwNoAccent(Alltrim(SZZ->ZZ_MAT))
    EndIf
        
Return(Self)
