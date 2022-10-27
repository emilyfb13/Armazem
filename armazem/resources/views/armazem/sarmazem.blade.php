@extends('principal')

@section('icones')
    <?php
    use App\Helpers\Helper; 
    ?>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('carrinho') }}" title="Meu Carrinho" role="button">
            <i class="fas fa-shopping-cart"></i>
            <span class="badge badge-success navbar-badge"> {{ Helper::countCarrinho()}}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" title="Tutoriais" role="button">
            <i class="fas fa-info-circle"></i>
        </a>
    </li>
@endsection

@section('titulo')
    <h3 style="color: #2A542D">Requisição ao Armazém</h3>
@endsection

@section('caminho')
    <li class="breadcrumb-item"><a href="{{ route('principal') }}">Home</a></li>
    <li class="breadcrumb-item active">Requisição ao Armazém</li>
@endsection

@section('message')
    <div class="not-visible d-flex align-items-center" id="msgload" name=>
        <strong>Carregando produtos por favor aguarde...</strong>
        <div class="spinner-border text-success ml-auto" role="status" aria-hidden="true"></div>
    </div>
@endsection

@section('titulo2')
    <h4 style="margin-top: 2%">
        <i class="nav-icon fas fa-clipboard-check"></i>
        Lista de requisições
    </h4>
@endsection

@section('complemento')
    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#exampleModalCenter">
        Filtro Avançado
    </button>
    <!-- Filtro Modal -->
    <div class="modal fade" id="exampleModalCenter" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Filtrar Requisições</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" method="get" action="{{ route('filtroSA') }}" >
                        <div class="form-group">
                            <label>Código da Solicitação</label>
                            <input name="codsa" type="text" placeholder="Digite o código da solicitação." class="form-control">
                            <label>Status</label>
                            <select class="form-control select2-single" name="status">
                                <option selected value="">Selecione um status.</option>
                                <option value="INCLUIDO"> Aguardando Atendimento </option>
                                <option value="PC"> Em processo de compra</option>
                                <option value="DISP"> Disponível para retirada</option>
                                <option value="FIM"> Entregue ou encerrado </option>
                            </select>
                            <label>Data Emissão</label>
                            <div class="input-group" lang="pt-BR">
                                <input type="text" name="daterange" class="form-control date-range-picker dates">
                            </div>
                            <label>Código do Produto</label>
                            <input name="codproduto" type="text" placeholder="Digite o código do produto ou parte dele." class="form-control">
                            <label>Produto</label>
                            <input name="produto" type="text" placeholder="Digite a descrição do produto ou parte dela." class="form-control">
                            <label>Observação</label>
                            <input name="obs" type="text" placeholder="Digite a observação ou parte dela." class="form-control">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                               <input class="btn btn-lg btn-outline-success" type="submit" value="Aplicar Filtro">
                            </div>
                            <p>*Nenhum campo é de preenchimento obrigatório.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#ModalLegenda">
        Legenda dos status
    </button>
    <!-- Filtro Modal -->
    <div class="modal fade" id="ModalLegenda" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Legenda dos Status de cada Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><i class="fas fa-circle" style="color: yellow"></i>  Requisição Incluida</p>
                    <p><i class="fas fa-circle" style="color: blue"></i>  Requisicao Encerrada</p>                                                        
                    <p><i class="fas fa-circle" style="color: #DC3545"></i>  Requisicao Entregue</p>                                                         
                    <p><i class="fas fa-circle" style="color: #28A745"></i>  Itens disponiveis para retirada</p>                    
                    <p><i class="fas fa-circle" style="color: orange"></i>  Em processo de compra</p>
                </div>
            </div>
        </div>
    </div>

    @if (isset($parametros))
        @if ($parametros > 0)
            <div class="col-4 m-0 p-0">
                <a class="badge badge-secondary" href="{{ route('saArmazem') }}"
                    title="Voltar para tela inicial de busca">
                    <i class="fas fa-times"></i>
                    Limpar filtro
                </a>
            </div>
        @endif
    @endif
@endsection

@section('conteudo')
    <div class="container-fluid m-4">
        <div class="row justify-content-center">
            <div class="col-4">
                <a href="{{ route('apiprodutos') }}">
                    <button class="btn btn-lg btn-outline-success" id="btnProdutos">
                        <i class="fa fa-plus"></i>
                        Incluir Nova Requisição
                        <div class="not-visiblein"id="btnload" name="btnload"> <span
                                class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>
                    </button>
                </a>
            </div>
        </div>
    </div>

    @if($sArmazem->total() == 0 &&  !(isset($parametros)))

    <div class="callout  callout-success" >
        <h4 class="callout-heading">Olá {{ Helper::nomeUser(session()->get('NomeUsuario')) }}!</h4>
        <p>Você ainda não possui nenhuma requisição ao armazém realizada no sistema, para realizar uma nova requisição, basta clicar no botão <strong>Incluir Nova Requisição</strong>  acima e selecionar os produtos desejados!</p>
        <hr style="border: 1px solid green;">
        <p class="mb-0">Caso esteja passando por dificuldades ou problemas técnicos entre em contato com a <strong> Equipe Protheus. </strong></p>
    </div>
    @elseif ($sArmazem->total() == 0 &&  (isset($parametros)))
    
    <div class="callout  callout-success" >
        <h4 class="callout-heading">Olá {{ Helper::nomeUser(session()->get('NomeUsuario')) }}!</h4>
        <p>Não encontramos nenhuma requisição ao armazém com os parâmetros informados, por favor tente realizar um novo filtro, para realizar uma nova requisição, basta clicar no botão <strong>Incluir Nova Requisição</strong>  acima e selecionar os produtos desejados!</p>
        <hr style="border: 1px solid green;">
        <p class="mb-0">Caso esteja passando por dificuldades ou problemas técnicos entre em contato com a <strong> Equipe Protheus. </strong></p>
    </div>
    

    @else
        <nav class="w-100 m-2">
            <div class="row justify-content-center titulo">
                
                <div class="col-md-2">Código</div>
                <div class="col-md-4">Requisitante</div>
                <div class="col-md-1"></div>
                <div class="col-md-2">Ações</div>
            </div>

            @foreach ($sArmazem as $armazem)
                <div class="modal fade" id="carrinhomodal{{ $armazem->ID }}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    <i class="fas fa-trash"></i>
                                    Remover <b>todos os itens da da S.A {{ $armazem->ID }}?
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <i class="fa fa-question-circle"></i>
                                Você tem certeza que deseja remover todos os itens
                                da sua solicitação ao armazém {{ $armazem->ID }} ? <br><br>
                                <b>Obs: Essa operação não poderá ser desfeita... </b>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <a href="{{ route('deleteall', ['id' => $armazem->ID]) }}" type="button"
                                    class="btn btn-danger">Remover</a>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-pills nav-sidebar flex-column mt-2" data-widget="treeview" id="{{ $armazem->ID }}"
                    role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a class="nav-link p-0">
                            <p class="right" style="color: black;" title="Clique para visualizar os itens.">
                                Visualizar os itens
                                <i class="itens fas fa-angle-left"></i>
                            </p>
                        </a>
                        <div class="row justify-content-center mt-2">
                            <div class=" col-md-2"> {{ $armazem->ID }} </div>
                            <div class=" col-md-4"> {{ Helper::nomeUser(session()->get('NomeUsuario')) }} </div>
                            <div class="col-md-1"></div>
                            <div class=" col-md-2">
                                <a href="{{ url('/saedit', ['id' => $armazem->ID]) }}" title="Editar e/ou visualizar a requisição.">
                                    <i class="far fa-edit"></i>
                                </a>

                                <a href="" data-toggle="modal" data-target="#carrinhomodal{{ $armazem->ID }}"
                                    title="Excluir a requisição.">
                                    <i class="fas fa-trash"></i>
                                </a>
                                </a>
                            </div>
                            
                        </div>

                        <ul class="nav nav-treeview" id="{{ $armazem->ID }}">
                            <div class="row titulo2 m-2">
                                <div class="col-1">Item</div>
                                <div class="col-md-2">Produto</div>
                                <div class="col-md-3">Descrição</div>
                                <div class="col-md-1">Qtde.</div>
                                <div class="col-md-1">UM</div>
                                <div class="col-md-3">Observação</div>
                                <div class="col-md-1">Status</div>
                            </div>
                            @foreach ($armazem->ITENS as $item)
                                <li class="nav-item">
                                    <div class="row m-2">
                                        <div class="col-1">{{ $item->ITEM }} </div>
                                        <div class="col-2">{{ $item->PRODUTO }}</div>
                                        <div class="col-3">{{ $item->DESCRICAO }}</div>
                                        <div class="col-1">{{ $item->QUANTIDADE }}</div>
                                        <div class="col-1">{{ $item->UNIDADEMEDIDA }}
                                        </div>
                                        <div class="col-3">{{ $item->OBSERVACAO }}</div>
                                        <div class="col-1">
                                            <nav class="navbar navbar-expand-sm ">
                                                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                                                    <ul class="navbar-nav mr-auto">
                                                        <li class="nav-item dropdown">
                                                            <a class="dropdown-togggle" href="#"
                                                                title="Visualizar detalhes do Status" id="navbarDropdown"
                                                                role="button" data-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                @if ($item->STATUS == 'Requisicao Incluida')
                                                                    <i class="fas fa-circle" style="color: yellow"></i>
                                                                @elseif ($item->STATUS == 'Requisicao Encerrada')
                                                                    <i class="fas fa-circle" style="color: blue"></i>
                                                                @elseif ($item->STATUS == 'Requisicao Entregue')
                                                                    <i class="fas fa-circle" style="color: #DC3545"></i>
                                                                @elseif ($item->STATUS == 'Itens disponiveis para retirada')
                                                                    <i class="fas fa-circle" style="color: #28A745"></i>
                                                                @else
                                                                    <i class="fas fa-circle" style="color: orange"></i>
                                                                @endif
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <div class="dropdown-menu" aria-labelledby="navbarDropdown"
                                                                style="padding-top:0%">
                                                                <h6 id="informacao">Detalhe da Requisição ao Armazém</h6>
                                                                <p class="m-2">Status da Requisição ao Armazém:
                                                                    {{ $item->STATUS }}</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </nav>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            @endforeach
        </nav>
    @endif

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-11"></div>
            <div class="col-1">
                {{ $sArmazem->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@endsection
