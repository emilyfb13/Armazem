#Include 'Totvs.ch'
#Include 'Protheus.ch'

User Function ClassProdutos()
Return

Class Produtos

    Data id             As STRING
    Data descricao      As STRING
    Data tipo           As STRING
    Data grupo          As STRING
    Data unidadeMedida  As STRING
    Data status         As STRING

    Data Disponivel      As FLOAT
    Data SaldoAtual      As FLOAT
    Data EmpenhadoSA     As FLOAT
    Data EntradaPrevista As FLOAT



    Method New(cCodigo) CONSTRUCTOR

EndClass

Method New (cCodProd) Class Produtos
    
    ::id            := ""
    ::descricao     := ""
    ::tipo          := ""
    ::grupo         := ""
    ::unidadeMedida := ""
    ::status        := ""

    ::Disponivel     := 0
    ::SaldoAtual     := 0
    ::EmpenhadoSA    := 0
    ::EntradaPrevista:= 0
    
    DbSelectArea("SB1")
    SB1->(DbSetOrder(1))
        
    If SB1->( DbSeek( xFilial("SB1") + cCodProd))
        ::id            := Alltrim(SB1->B1_COD)
        ::descricao     := Alltrim(SB1->B1_DESC)
        ::tipo          := SB1->B1_TIPO
        ::grupo         := SB1->B1_GRUPO
        ::unidadeMedida := SB1->B1_UM
        ::Status        := IIF( SB1->B1_MSBLQL == "1", "0", "1")
        
        DbSelectArea("SB2")
        SB2->(DbSetOrder(1))

        IF SB2->( DbSeek( xFilial("SB2") + SB1->B1_COD + SB1->B1_LOCPAD))

            ::Disponivel     := SB2->B2_QATU - SB2->B2_RESERVA - SB2->B2_QEMP - SB2->B2_QACLASS - SB2->B2_QEMPSA - SB2->B2_QEMPPRJ - SB2->B2_QTNP + SB2->B2_QNPT - SB2->B2_QEMPPRE
            ::SaldoAtual     := SB2->B2_QATU // Saldo Fisico Atual
            ::EmpenhadoSA    := SB2->B2_QEMPSA
            ::EntradaPrevista:= SB2->B2_SALPEDI 

        EndIF


    EndIf
  
Return(Self)
