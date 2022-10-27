@extends('principal')

@section('titulo2')
@endsection

@section('conteudo')
    <?php
    use App\Helpers\Helper; 
    ?>
    <div class="container">
        <h2 style="margin-top: 2%; color:#2A542D;">
            Bem vindo(a) ao Armazém Web {{ Helper::nomeUser(session()->get('NomeUsuario')) }}
        </h2>
    </div>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box" style="background: gold">
                    <div class="inner">
                        <h3> <?= $inc ?> </h3>
                        <h5>Aguardando atendimento</h5>
                        <br>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    @if ($inc != 0)
                    <a href="{{ route('dashInc') }}" class="small-box-footer">Mais informação <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @else
                    <a href="#" class="small-box-footer">Sem informações <i
                        class="fas fa-arrow-circle-right"></i></a>
                    @endif
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box" style="background: orange">
                    <div class="inner">
                        <h3> <?= $pc ?> </h3>
                        <h5>Em processo de compra</h5>
                        <br>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    @if ($pc != 0)  
                    <a href="{{ route('dashPC') }}" class="small-box-footer">Mais informação <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @else
                    <a href="#" class="small-box-footer">Sem informações<i
                        class="fas fa-arrow-circle-right"></i></a>
                    
                    @endif
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-success">
                    <div class="inner">
                        <h3> <?= $disp ?> </h3>
                        <h5>Disponíveis para entrega</h5>
                        <br>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    @if ($disp != 0) 
                    <a href="{{ route('dashDisp') }}" class="small-box-footer">Mais informação <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @else
                    <a href="#" class="small-box-footer">Sem informações <i
                        class="fas fa-arrow-circle-right"></i></a>
                    @endif
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3> <?= $fim ?> </sup></h3>
                        <h5>Entregues ou encerradas</h5>
                        <br>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    @if ($fim != 0)  
                    <a href="{{ route('dashFim') }}" class="small-box-footer" >Mais informação <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @else 
                     <a href="#"  class="small-box-footer" >Sem informações <i
                        class="fas fa-arrow-circle-right"></i></a>  
                     @endif        
                </div>
            </div>
        </div>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="card ">
                    <h5 class="card-header text-center" style="font-weight: bold">
                        Contato
                    </h5>
                    <div class="card-body">
                        <h5 class="card-title" style="font-weight: bold">Para erros e melhorias entre em contato com o suporte através dos contatos abaixo:</h5>
                        
                    </p>
                    <p class="card-text text-center">
                        Suporte1: (XX) XXXX-XXXX
                        <br>
                        Suporte2: (XX) XXXX-XXXX
                        <br>
                        Suporte3: (XX) XXXX-XXXX
                        <br>
                        Suporte4: (XX) XXXX-XXXX
                    </p>
                        <div class="text-center">
                            
                            <a href="#">
                                <button class="btn btn-outline-success">
                                    Para elogios e sugestões anônimas clique aqui!
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
