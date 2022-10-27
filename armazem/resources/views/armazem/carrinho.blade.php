@extends('principal')

@section('icones')
    <?php
        use App\Helpers\Helper; 
    ?>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('carrinho') }}" title="Meu Carrinho"role="button">
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

@section('titulo')
    <h3 style="color: #2A542D">Requisição ao Armazém</h3>
@endsection

@section('caminho')
    <li class="breadcrumb-item"><a href="{{ route('principal') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('saArmazem') }}">Requisição ao Armazém</a></li>
    <li class="breadcrumb-item"><a href="{{ route('apiprodutos') }}">Lista de Produtos</a></li>
    <li class="breadcrumb-item active">Carrinho</li>
@endsection

@section('message')
    @if (session('remover'))
        <div class="alert" style="background-color: #d4edda">
            <i class="fas fa-check-circle"></i>
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            {{ session('remover') }}
        </div>
    @endif
@endsection

@section('titulo2')
    <h4 style="margin-top: 2%">
        <i class="fa fa-shopping-cart"></i>
        Carrinho de Requisições
    </h4>
@endsection

@section('conteudo')

@if ( $produtos == null)
    <div class="callout  callout-success" >
        <h4 class="callout-heading">Olá {{ Helper::nomeUser(session()->get('NomeUsuario')) }}!</h4>
        <p>Você ainda não possui nenhum produto no carrinho, para selecionar um produto basta voltar para listagem de produtos e adicionar um item clicando no ícone do carrinho  <i class="fa fa-cart-plus" style="color: #2A542D"></i></p>
    </div>
@else    
    <nav class="w-100 m-2">
        <ul class="list-group">
            <li style="display: block;">
                <div class="row titulo m-1">
                    <div class="col-md-5">Produto</div>
                    <div class="col-md-1">Qtde.</div>
                    <div class="col-md-3">Centro de Custo</div>
                    <div class="col-md-2">Observação</div>
                </div>
            </li>
            @if (isset($produtos) && $produtos != null)
                <form enctype="multipart/form-data" method="post" action="{{ route('postsa') }}" id="formSA">

                    <li class="list-group-item">
                        {!! csrf_field() !!}

                        @foreach ($produtos as $key => $produto)
                            <div class="modal fade" id="carrinhomodal{{ $produto['produto'] }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                <i class="fas fa-trash"></i>
                                                Remover <b> {{ $produto['produto'] }} </b> do carrinho? </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <i class="fa fa-question-circle"></i>
                                            Você tem certeza que deseja remover o produto <b>{{ $produto['produto'] }}
                                            </b> do seu
                                            carrinho de solicitações?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancelar</button>
                                            <a href="{{ route('deletecar', ['id' => $produto['produto']]) }}"
                                                type="button" class="btn btn-danger">Remover</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="border-bottom:1px solid #B3B3B3">
                                <div class="row col-md-12 m-0 p-0">
                                    <div class="col-md-5 m-0 p-0">
                                        <p class="font-weight-bold mr-1"> {{ $produto['descr'] }}</p>
                                        <div class="row m-0 p-0" name="produto" value="{{ $produto['produto'] }}">
                                            <p class="mr-1 m-0 p-0">Código:</p> {{ $produto['produto'] }}
                                        </div>
                                        <div class="row m-0 p-0">
                                            <p class="mr-1 m-0 p-0">Und. Medida: {{ $produto['unidade'] }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-1 text-sm-center">
                                        <input type="number" min="1" value="1"
                                            name="qntd[{{ $key }}]" class="form-control text-center">
                                        <div>
                                            <a style="font-size: small;" href="" data-toggle="modal"
                                                data-target="#carrinhomodal{{ $produto['produto'] }}"
                                                title="Remover item do carrinho">
                                                remover
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <select class="form-control select2-single" name="centros[{{ $key }}]" >
                                            <option value=''> Selecione </option>
                                            @foreach ($centros as $centro)
                                                <option value="{{ $centro->NUM }}">{{ $centro->NUM }} - {{ $centro->DESCRICAO }}</option>
                                            @endforeach
                                        </select>                        
                                    </div>

                                    <div class=" col-md-3">
                                        <input name="obs[{{ $key }}]" type="text" class="form-control">
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </li>
                    <br>
                    <div class="container-fluid">
                        <div class="row row justify-content-center">
                            <div class="col-md-3">
                                <input class="btn btn-lg btn-outline-success" type="submit"
                                    value="Finalizar Requisição">
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </ul>
    </nav>
    @endif

@endsection
