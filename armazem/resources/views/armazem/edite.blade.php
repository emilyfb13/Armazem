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
    <li class="breadcrumb-item active">Edição</li>
@endsection


@section('message')
    @if (session('remover'))
        <div class="alert" style="background-color: #d4edda">
            <i class="fas fa-check-circle"></i>
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            {{ session('remover') }}
        </div>
    @endif
    @if ($status == 0 )
        <div class="alert" style="background-color:  #f5e2aa">
           <h5> <i class="fas fa-info-circle"></i> Atenção! </h5>
            Olá <strong>{{ Helper::nomeUser(session()->get('NomeUsuario')) }}</strong>, essa solicitação não pode mais ser alterada
            pois não está com o status de <strong>Requisição Incluida</strong>, você está apenas no modo de visualização!
        </div>
    @endif  
@endsection

@section('titulo2')
    <h4 style="margin-top: 2%">  <i class="far fa-edit"></i>  Editar Requisição</h4>
@endsection

@section('conteudo')
    <nav class="w-100 m-2">
        <ul class="list-group">
            <li style="display: block;">
                    <div class="row titulo m-1">
                        <div class="col-md-5" >Produto</div>
                        <div class="col-md-1" >Qtde.</div>
                        <div class="col-md-3" >Centro de Custo</div>
                        <div class="col-md-1" >Necessi.</div>
                        <div class="col-md-2" >Observação</div>
                    </div>
            </li>
            @if (isset($produtos) && $produtos != null)
                <form enctype="multipart/form-data" method="post" action="{{ route('putsa') }}" id="formeditSA">
                    
                    <li class="list-group-item">
                        {!! csrf_field() !!}

                        @foreach ($produtos as $key => $produto)

                        <div class="modal fade" id="carrinhomodal{{  $produto->PRODUTO  }}" tabindex="-1"
                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                            <i class="fas fa-trash"></i>
                                            Remover <b> {{ $produto->DESCRICAO }}</b> da S.A {{ $ID }}? </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                       
                                        <i class="fa fa-question-circle"></i>
                                        Você tem certeza que deseja remover o produto <b> {{ $produto->PRODUTO }} </b>
                                        </b> da sua solicitação ao armazém  {{$ID}} ? <br><br>
                                       <strong>Obs:</strong> Essa operação não poderá ser desfeita... </b>
                                     
                
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancelar</button>
                                        <a href="{{ route('deleteitem', ['id' =>  $ID ,'item' => $produto->ITEM  ]) }}"
                                            type="button" class="btn btn-danger">Remover</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                           
                            <div class="form-group row" style="border-bottom:1px solid #B3B3B3">
                                <div class="row col-md-12 m-0 p-0" >
                                    <div class="col-md-5 m-0 p-0"  >
                                        <p class="font-weight-bold mr-1"> {{ $produto->DESCRICAO}}</p>
                                        <div class="row m-0 p-0" name="produto" value="{{ $produto->PRODUTO }}">
                                            <input TYPE="hidden" name="codigo[{{ $produto->ITEM  }}]" value="{{ $produto->PRODUTO }}" class="mr-1 m-0 p-0">Código:</input> {{ $produto->PRODUTO }}
                                        </div>
                                        <div class="row m-0 p-0" >
                                            <p class="mr-1 m-0 p-0" >Und. Medida: {{ $produto->UNIDADEMEDIDA }}</p>
                                        </div>
                                        <div class="row m-0 p-0" >
                                            <p class="mr-1 m-0 p-0" >Data de Emissão da Solicitação: {{ $produto->EMISSAO }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-1 text-sm-center">
                                        <input type="number" min="1" value="{{$produto->QUANTIDADE}}" '@if ($status != 1) disabled @endif' 
                                            name="qntd[{{ $produto->ITEM }}]" class="form-control text-center">
                                        @if ($status != 0 )    
                                            <div>
                                                <a style="font-size: small;" href="" data-toggle="modal"
                                                    data-target="#carrinhomodal{{ $produto->PRODUTO }}"
                                                    title="Remover item do carrinho" >
                                                    remover
                                                </a>
                                            </div>
                                        @endif
                                    </div>


                              

                                    <div class=" col-md-3">
                                        <select class="form-control select2-single" name="centros[{{ $produto->ITEM }}]" '@if ($status != 1) disabled @endif' >
                                            @foreach ($centros as $centro)
                                            @if ($centro->NUM == $produto->CENTROCUSTO)
                                            <option selected value="{{ $centro->NUM }}">{{ $centro->NUM }} -
                                                {{ $centro->DESCRICAO }}</option>
                                            @else
                                                <option value="{{ $centro->NUM }}">{{ $centro->NUM }} -
                                                    {{ $centro->DESCRICAO }}</option>
                                            @endif        
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class=" col-md-2">
                                        <input name="obs[{{ $produto->ITEM }}]" type="text" value="{{$produto->OBSERVACAO}}"
                                            class="form-control" '@if ($status != 1) disabled @endif' >
                                    </div>

                                </div>
                            </div>
                        @endforeach
                        <div class="row m-0 p-0" name="IDSA" value="{{ $ID}}">
                            <input TYPE="hidden" name="IDSA" value="{{ $ID}}" class="mr-1 m-0 p-0"></input>
                        </div>
                    </li>
                    <br>
                    <div class="container-fluid">
                        <div class="row row justify-content-center">
                            <div class="col-md-3">
                                <input class="btn btn-lg btn-outline-success" type="submit"  '@if ($status != 1) disabled @endif' 
                                    value="Finalizar Alteração">
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </ul>
    </nav>




@endsection
