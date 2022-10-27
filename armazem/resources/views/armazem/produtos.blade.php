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
        <a class="nav-link" href="#" role="button">
            <i class="fas fa-info-circle"></i>
        </a>
    </li>
@endsection

@section('Menu2')
   
@endsection

@section('titulo')
    <h3 style="color: #2A542D">Requisição ao Armazém</h3>
@endsection


@section('caminho')
    <li class="breadcrumb-item"><a href="{{ route('principal') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('saArmazem') }}">Requisição ao Armazém</a></li>
    <li class="breadcrumb-item active">Lista de Produtos</li>
@endsection



@section('titulo2')
    <h4 style="margin-top: 2%">
        <i class=" fa fa-list-ul"></i>
        Lista de produtos</h4>
@endsection


@section('complemento')
<div class="row justify-content-end"> 
<!-- SidebarSearch Form -->
    <div class="col-7">
    <form action="{{ route('filtrarpalavra') }}" class="form-inline">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Palavra-chave" aria-label="Search" name="palavrachave"
                title="Pesquisar palavra-chave" id="busca_chave">
            <div class="input-group-append">
                <button type="submit" class="btn pesquisar" title="Confirmar busca">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </form>
    </div>
    <div class="col-5">
        <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#exampleModalCenter">
            Filtro Avançado
        </button>
        <!-- Filtro Modal -->
        <div class="modal fade" id="exampleModalCenter" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Filtrar Produtos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form enctype="multipart/form-data" action="{{ route('filtroProd') }}" >
                            <div class="form-group">
                                <label>Código do Produto</label>
                                <input name="codproduto" type="text" placeholder="Digite o código do produto ou parte dele." class="form-control">
                                <label>Produto</label>
                                <input name="produto" type="text" placeholder="Digite a descrição do produto ou parte dela." class="form-control">
                                <label>Tipo</label>
                                <select class="form-control select2-single" name="tipo">
                                    <option selected value="">Selecione um tipo.</option>
                                    <option value="AI"> AI </option>
                                    <option value="MC"> MC</option>
                                </select>
                                <label>Grupo</label>
                                <input name="grupo" type="text" placeholder="Digite o número do grupo ou parte dele." class="form-control">
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
    </div>
    
  

    @if (isset($aParametros))
        @if (isset($parametros))
            @if (isset($aParametros['palavrachave']))
                @if ($aParametros['palavrachave'] != null)
                    <div class="col-8">
                        <p class="m-0 p-0" style="font-size: smaller">
                            <b>Filtro aplicado: </b>{{ $aParametros['palavrachave'] }}
                        </p>
                    </div>    
                @endif
            @endif
        @endif
        <?php
            $maxparametros = sizeof($aParametros);       
        ?>
        @if ($maxparametros > 0)
            <div class="col-4 m-0 p-0">
                <a class="badge badge-secondary" href="{{ route('apiprodutos') }}"
                    title="Voltar para tela inicial de busca">
                    <i class="fas fa-times"></i>
                    Limpar filtro
                </a>
            </div>
        @endif
    @endif
</div>  
@endsection

@section('conteudo')
    <nav class="w-100 m-2">
        <ul class="list-group">
            <li style="display: block;">
                <div class="row titulo m-2">
                    <div class="col-md-3">Código</div>
                    <div class="col-md-4">Descrição</div>
                    <div class="col-md-1">Saldo</div>
                    <div class="col-md-1">Tipo</div>
                    <div class="col-md-2">Grupo</div>
                    <div class="col-md-1"></div>
                </div>
            </li>
            @foreach ($aProdutos as $produto)
                <li class="list-group-item">
                    <form>
                        <div class="form-group row m-2">
                            <div class="col-md-3">{{ $produto->ID }}</div>
                            <div class="col-md-4">{{ $produto->DESCRICAO }}</div>
                            <div class="col-md-1">{{ $produto->SALDOATUAL }}</div>
                            <div class="col-md-1">{{ $produto->TIPO }}</div>
                            <div class="col-md-2">{{ $produto->GRUPO }}</div>
                            <div class="col-md-1">
                                <a title="Adicionar ao Carrinho"
                                    href="{{ route('postcar', ['id' => $produto->ID, 'descricao' => str_replace('/', '', $produto->DESCRICAO), 'unidade' => $produto->UNIDADEMEDIDA]) }}">
                                    <i class="fa fa-cart-plus" style="color: #2A542D"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </li>
            @endforeach
        </ul>
    </nav>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-2"></div>
            <div class="col-7">
                @if (isset($aParametros))
                    {{ $aProdutos->appends($aParametros)->links('pagination::bootstrap-4') }}
                @else
                    {{ $aProdutos->links('pagination::bootstrap-4') }}
                @endif
            </div>
            <div class="col-3">
                <a href="{{ route('carrinho') }}">
                    <button class="btn btn-outline-success"><i class="fa fa-shopping-cart m-1"></i>Ir Para o
                        Carrinho</button>
                </a>
            </div>
        </div>
    </div>
@endsection
