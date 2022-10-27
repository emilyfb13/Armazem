<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Armazem Web | Verificação</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/faviconV2.png') }}" />


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/fontawesome-free/css/all.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('adminlte/css/adminlte.css') }}">
    <!-- Meu css -->
    <link rel="stylesheet" href="{{ URL::asset('css/geral.css') }}">


</head>



<body class="hold-transition login-page" id="particles-js">
    <div class="login-box">
        <div class="login-logo">
            <a href="#" style="color: #fff"><b>Armazém</b>WEB</a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Informe seus dados de verificação.</p>

                @if (session('erro'))
                    <div class="alert" style="background-color: #f2dede">
                        <i class="fas fa-exclamation-triangle"></i>
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        {{ session('erro') }}
                    </div>
                @endif

                <form enctype="multipart/form-data" method="POST" action="{{ route('getVerifica') }}">
                    {!! csrf_field() !!}

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="cpf" id="cpf" placeholder="CPF - Apenas númeors">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('cpf'))
                        <span class="text-danger">
                            {{ $errors->first('cpf') }}
                        </span>
                    @endif
                    <div class="input-group mb-1">
                        <input type="password" class="form-control" name="matricula" id="matricula" placeholder="Matricula - Apenas números">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    @if ($errors->has('matricula'))
                        <span class="text-danger">
                            {{ $errors->first('matricula') }}
                        </span>
                    @endif

                    
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="mae" id="mae" placeholder="Nome da mãe completo sem acento.">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn  btn-success  btn-block "> Confirmar <i
                                    class="fa fa-key"></i> </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ URL::asset('adminlte/plugins/jquery/jquery.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ URL::asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('adminlte/js/adminlte.js') }}"></script>
    <!-- Particles-js -->
    <script src="{{ URL::asset('js/particlesJS/particles.js') }}"></script>
    <script src="{{ URL::asset('js/particlesJS/app.js') }}"></script>


</body>

</html>
