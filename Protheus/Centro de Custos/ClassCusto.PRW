#Include 'Totvs.ch'
#Include 'Protheus.ch'

User Function ClassCusto()
Return

Class Custos

    Data num             As STRING
    Data descricao      As STRING

    Method New(cNum) CONSTRUCTOR

EndClass

Method New (cCTT) Class Custos
    
    ::num            := ""
    ::descricao     := ""
    
    DbSelectArea("CTT")
    CTT->(DbSetOrder(1))
        
    If CTT->( DbSeek( xFilial("CTT") + cCTT))
        ::num            := Alltrim(CTT->CTT_CUSTO)
        ::descricao     := FwNoAccent(Alltrim(CTT->CTT_DESC01))
    EndIf
        
Return(Self)
