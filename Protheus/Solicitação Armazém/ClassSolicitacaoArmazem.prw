#Include 'Totvs.ch'
#Include 'Protheus.ch'

User Function ClassSolicitacaoArmazem()
Return

Class SolicitacaoArmazem

    Data id             As STRING
    Data itens          As ARRAY
    
    Method New(numero) CONSTRUCTOR

EndClass


Method New (numero) Class SolicitacaoArmazem
    
    Local solicitante   := ""
    Local item          := ""
    Local produto       := ""
    Local descricao     := ""
    Local unidadeMedida := ""
    Local quantidade    := ""
    Local emissao       := ""
    Local centroDeCusto := ""
    Local observacao    := ""
    Local status        := ""
    Local statusSC      := ""
    Local previsaoPC    := ""

    ::id            := ""
    ::itens         := {}

    DbSelectArea("SCP")
    SCP->(DbSetOrder(1))
    SCP->(DbGoTop())

    If SCP->( DbSeek( xFilial("SCP") + numero))
        
        ::id            := SCP->CP_NUM
        
        While SCP->CP_NUM == numero
            
            solicitante   := Alltrim(SCP->CP_SOLICIT)
            item          := Alltrim(SCP->CP_ITEM)
            produto       := Alltrim(SCP->CP_PRODUTO)
            descricao     := Alltrim(SCP->CP_DESCRI)
            unidadeMedida := SCP->CP_UM
            quantidade    := SCP->CP_QUANT
            emissao       := DTOC(SCP->CP_EMISSAO)
            centroDeCusto := Alltrim(SCP->CP_CC)
            observacao    := Alltrim(SCP->CP_OBS)
            
           
            IF (!EMPTY(SCP->CP_STATUS) .AND. SCP->CP_PREREQU == "S" .AND. SCP->CP_QUANT == SCP->CP_QUJE)
                status := "Requisicao Entregue"
            ELSEIF (EMPTY(SCP->CP_STATUS) .AND. EMPTY(SCP->CP_PREREQU)) 
                status := "Requisicao Incluida"
            ELSEIF (!EMPTY(SCP->CP_STATUS) .AND. SCP->CP_PREREQU == "S" .AND. ( SCP->CP_QUANT > SCP->CP_QUJE))  
                status := "Requisicao Encerrada"
            ELSE
                DbSelectArea("SCQ")
                SCQ->(DbSetOrder(1))
                If SCQ->( DbSeek( xFilial("SCQ") + SCP->CP_NUM + SCP->CP_ITEM + ""))
                    DbSelectArea("SC1")
                    SC1->(DbSetOrder(1))
                    If (SCQ->CQ_QTDISP > 0)
                        status := "Itens disponiveis para retirada"
                    ElseIf SC1->( DbSeek(xFilial("SC1") + SCQ->CQ_NUMSC + SCQ->CQ_ITSC))
                        statusSC := IIF(C1_QUJE == 0 .AND. C1_COTACAO == SPACE(LEN(C1_COTACAO)) .AND. C1_APROV $ " ,L" .AND. EMPTY(C1_RESIDUO), " Solicitacao de Compra Pendente", IIF(C1_TPSC != "2" .And. C1_QUJE == 0 .And. Alltrim(C1_COTACAO) <> "" .And. Alltrim(C1_IMPORT) <>"S", "Solicitacao de Compra em Cotacao",  IIF(C1_QUJE == 0 .AND. (C1_COTACAO == SPACE(LEN(C1_COTACAO)) .OR. C1_COTACAO == "IMPORT") .AND. C1_APROV == "R", "Solicitacao de Compra Rejeitada", IIF(C1_TIPO == 2, "Solicitacao de Compra de Importacao", IIF(C1_QUJE == 0 .And. (C1_COTACAO == Space(Len(C1_COTACAO)) .Or. C1_COTACAO == "IMPORT") .And. C1_APROV == "B", "Solicitacao de Compra Bloqueada", IIF(!EMPTY(C1_RESIDUO), " Solicitacao de Compra Eliminada Por Residuo", IIF((C1_QUJE > 0) .AND. (!EMPTY(C1_PEDIDO)) .AND. (EMPTY(C1_RESIDUO)), "Pedido de Compra Gerado", "")))))))
                        DbSelectArea("SC7")
                        SC7->(DbSetOrder(1))                      
                        If SC7->( DbSeek(xFilial("SC7") + SC1->C1_PEDIDO + SC1->C1_ITEMPED))
                            previsaoPC := DTOC(SC7->C7_DATPRF)
                        Endif                      
                    Endif
                Endif

                IF statusSC != "Pedido de Compra Gerado" .AND. !EMPTY(statusSC)
                    status := statusSC
                ELSEIF statusSC == "Pedido de Compra Gerado" .AND. EMPTY(CTOD(previsaoPC))
                    status := statusSC
                ELSEIF statusSC == "Pedido de Compra Gerado" .AND. !EMPTY(CTOD(previsaoPC))
                    status := "Previsao de Entrega:" + previsaoPC + ""
                ENDIF
            ENDIF   

            aAdd(::itens, ItensSolicitacaoArmazem():New(solicitante,;
                                                    item,;
                                                    produto,;
                                                    descricao,;
                                                    unidadeMedida,;
                                                    quantidade,; 
                                                    emissao,; 
                                                    centroDeCusto,; 
                                                    observacao,;
                                                    status))
        SCP->(DbSkip())    
        End        
    EndIf

Return(Self)
