#Include 'Totvs.ch'
#Include 'Protheus.ch'

User Function ClassItensSolicitacaoArmazem()
Return

Class ItensSolicitacaoArmazem

    Data solicitante    As STRING
    Data item           As STRING
    Data produto        As STRING
    Data descricao      As STRING
    Data unidadeMedida  As STRING
    Data Quantidade     As STRING
    Data emissao        As STRING
    Data CentroCusto    As STRING
    Data observacao     As STRING
    Data status         As STRING

    Method New(solicitante,item, produto, descricao, unidadeMedida, quantidade, emissao, centroDeCusto, observacao, status) CONSTRUCTOR

EndClass

Method New (solicitante,item, produto, descricao, unidadeMedida, quantidade, emissao, centroDeCusto, observacao, status) Class ItensSolicitacaoArmazem

            ::solicitante   := solicitante
            ::item          := item
            ::produto       := produto
            ::descricao     := descricao
            ::unidadeMedida := unidadeMedida
            ::quantidade    := quantidade
            ::emissao       := emissao
            ::centroCusto   := centroDeCusto
            ::observacao    := observacao
            ::status        := status

Return (Self)
