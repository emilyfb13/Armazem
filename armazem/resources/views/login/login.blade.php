<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Armazem Web | Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/faviconV2.png') }}" />


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{('adminlte/plugins/fontawesome-free/css/all.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ ('adminlte/css/adminlte.css') }}">
    <!-- Meu css -->
    <link rel="stylesheet" href="{{ ('css/geral.css') }}">


</head>


<!--<body id="particles-js"> -->

<body class="hold-transition login-page" id="particles-js">
    <div class="login-box">
        <div class="login-logo">
            <a href="#" style="color: #fff"><b>Armazém</b>WEB</a>
        </div>

        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Informe suas credencias para acessar o sistema</p>

                @if (session('erro'))
                    <div class="alert" style="background-color: #f2dede">
                        <i class="fas fa-exclamation-triangle"></i>
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        {{ session('erro') }}
                    </div>
                @elseif (session('sucesso'))
                    <div class="alert" style="background-color: #d4edda">
                        <i class="fas fa-check-circle"></i>
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        {{ session('sucesso') }}
                    </div>
                @endif

                <form enctype="multipart/form-data" method="POST" action="{{ route('getLogin') }}">
                    {!! csrf_field() !!}

                    <div class="input-group ">
                        <input type="text" class="form-control" name="user" id="user" placeholder="Nome de Usuário (CPF)">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    @if ($errors->has('user'))
                        <span class="text-danger">
                            {{ $errors->first('user') }}
                        </span>
                    @endif
                    <br>
                    <div class="input-group">
                        <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    @if ($errors->has('senha'))
                        <span class="text-danger">
                            {{ $errors->first('senha') }}
                        </span>
                    @endif
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn  btn-success  btn-block "> Entrar </button>
                        </div>
                    </div>
                </form>
                <br>
                <button type="button" class="btn btn-block btn-social btn-primary" data-toggle="modal" data-target="#cadastrar">
                    Cadastre-se
                </button>
                <!-- Filtro Modal -->
                <div class="modal fade" id="cadastrar" role="dialog" aria-labelledby="cadastrar"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de novo usuário</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form enctype="multipart/form-data" method="POST" action="{{ route('postLogin') }}">
                                    {!! csrf_field() !!}
                                        <input type="text" class="form-control" name="cpf" id="cpf" placeholder="CPF - Apenas númeors">
                                        @if ($errors->has('cpf'))
                                            <span class="text-danger">
                                                {{ $errors->first('cpf') }}
                                            </span>
                                        @endif
                                        <br>
                                        <input type="text" class="form-control" name="matricula" id="matricula" placeholder="Matricula - Apenas números">
                                        @if ($errors->has('matricula'))
                                            <span class="text-danger">
                                                {{ $errors->first('matricula') }}
                                            </span>
                                        @endif
                                        <br>
                                        <input type="text" class="form-control" name="mae" id="mae" placeholder="Nome da mãe completo sem acento">
                                        <br>
                                        <input type="password" class="form-control" name="senha" id="senha" placeholder="Digite uma senha">
                                        @if ($errors->has('senha'))
                                            <span class="text-danger">
                                                {{ $errors->first('senha') }}
                                            </span>
                                        @endif
                                        <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn  btn-success  btn-block "> Cadastrar </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <button type="button" class="btn btn-block btn-social btn-warning" data-toggle="modal" data-target="#exampleModalCenter">
                    Alterar Senha
                </button>
                <!-- Filtro Modal -->
                <div class="modal fade" id="exampleModalCenter" role="dialog" aria-labelledby="exampleModalCenterTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Alterar Senha</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form enctype="multipart/form-data" method="POST" action="{{ route('putLogin') }}">
                                    {!! csrf_field() !!}
                                        <input type="text" class="form-control" name="cpf" id="cpf" placeholder="CPF - Apenas númeors">
                                        @if ($errors->has('cpf'))
                                            <span class="text-danger">
                                                {{ $errors->first('cpf') }}
                                            </span>
                                        @endif
                                        <br>
                                        <input type="text" class="form-control" name="matricula" id="matricula" placeholder="Matricula - Apenas números">
                                        @if ($errors->has('matricula'))
                                            <span class="text-danger">
                                                {{ $errors->first('matricula') }}
                                            </span>
                                        @endif
                                        <br>
                                        <input type="text" class="form-control" name="mae" id="mae" placeholder="Nome da mãe completo sem acento">
                                        <br>
                                        <input type="password" class="form-control" name="senha" id="senha" placeholder="Nova Senha">
                                        @if ($errors->has('senha'))
                                            <span class="text-danger">
                                                {{ $errors->first('senha') }}
                                            </span>
                                        @endif
                                        <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn  btn-success  btn-block "> Alterar </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ ('adminlte/plugins/jquery/jquery.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ ('adminlte/plugins/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ ('adminlte/js/adminlte.js') }}"></script>
    <!-- Particles-js -->
    <script src="{{ ('js/particlesJS/particles.js') }}"></script>
    <script src="{{ ('js/particlesJS/app.js') }}"></script>


</body>

</html>
