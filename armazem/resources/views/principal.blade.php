<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Armazém Web</title>
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

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ URL::asset('css/select2.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/select2bs.css') }}">

    <!-- Data date-range-picker css -->
    <link rel="stylesheet" href="{{ URL::asset('css/daterangepicker.css') }}">

    <!-- Color picker -->
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap-colorpicker.css') }}">

    <!-- Data date-range-picker css -->
    <link rel="stylesheet" href="{{ URL::asset('css/daterangepicker.css') }}">

    <!-- Color picker -->
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap-colorpicker.css') }}">


    @yield('head')

</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <?php
           use App\Helpers\Helper; 
        ?>
        <!-- Barra Superios -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" title="Reduzir Menu" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="" class="nav-link" style="color: #2A542D; padding-top:0%">
                        <h2>Armazém Web</h2>
                    </a>
                </li>
            </ul>

            <!-- Icones -->
            <ul class="navbar-nav ml-auto">
                <!-- User -->
                <nav class="navbar navbar-expand-sm ">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item dropdown">
                                <a class="dropdown-togggle" href="#" title="Detalhes de login" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user"></i>
                                    <span> {{ Helper::nomeUser(session()->get('NomeUsuario')) }} </span>
                                    <i class="fas fa-angle-down"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="padding-bottom:0%">
                                    <div class="text-center"> Bem Vindo(a)
                                        {{ Helper::nomeUser(session()->get('NomeUsuario')) }}</div>
                                    <div id="sair"><a data-target=".bs-example-modal-sm" data-toggle="modal"
                                            id="sair" href="">Sair</a></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                @yield('icones')
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" title="Expandir tela" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        

        <!-- Container lateral -->
        <aside class="main-sidebar  sidebar-light-primary elevation-4 menu">
            <!-- Logo -->
            <a href="{{ route('principal') }}" class="brand-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo">
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
               
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a> Bem Vindo(a) {{ Helper::nomeUser(session()->get('NomeUsuario')) }} </a>
                    </div>
                </div>
                
                <!-- Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" id="menu"
                        role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('saArmazem') }}" class="nav-link pl-0 pr-2">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                Requisição ao Armazém
                                <i class="right fas fa-angle-left ml-2" style="right:1rem"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('saArmazem') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Requisições</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('apiprodutos') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Incluir Nova Requisição</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Conteudo -->
        <div class="content-wrapper">
            <!-- Cabeçalho conteudo -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            @yield('titulo')
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('caminho')
                            </ol>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            @yield('message')
                            @if (session('sucesso'))
                                <div class="alert" style="background-color: #d4edda">
                                    <i class="fas fa-check-circle"></i>
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    {{ session('sucesso') }}
                                </div>
                            @endif
                            @if (session('erro'))
                                <div class="alert" style="background-color: #f2dede">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    {{ session('erro') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </section>

            <!-- Container Conteudo -->
            <section class="content">

                <!-- Box -->
                <div class="card card-solid">
                    <div class="card-body">
                        <!-- Cabeçalho -->
                        <div class="row justify-content-between">
                            <div class="col-4">
                                @yield('titulo2')
                            </div>
                            <div class="col-4">
                                <!-- SidebarSearch Form -->
                                @yield('complemento')
                            </div>
                        </div>
                        <!-- Requisições -->
                        <div class="row mt-4">
                            @yield('conteudo')
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!--Modal de DESEJA REALMENTE SAIR?-->
        <div tabindex="-1" class="modal bs-example-modal-sm" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4> Sair do Sistema <i class="fa fa-sign-out-alt"></i></h4>
                    </div>
                    <div class="modal-body">
                        <i class="fa fa-question-circle"></i>
                        Você realmente quer sair do sistema? <br><br>
                        Caso existam produtos no carrinho os mesmos serão <b>removidos!
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-danger btn-block" href="{{ route('sair') }}">Sair</a>
                    </div>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Versão</b> 1.0.0
            </div>
            <strong>Copyright &copy; 2022.</strong> Todos os direitos reservados.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ URL::asset('adminlte/plugins/jquery/jquery.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ URL::asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('adminlte/js/adminlte.js') }}"></script>
    <!-- Js Protheus -->
    <script src="{{ URL::asset('js/protheus.js') }}"></script>

    <!-- Select2  -->
    <script src="{{ URL::asset('js/select2.js') }}"></script>

    <!--Color picker  -->
    <script src="{{ URL::asset('js/bootstrap-colorpicker.js') }}"></script>


    <!--Moment  -->
    <script src="{{ URL::asset('js/moment.min.js') }}"></script>

    <!-- date-range-picker js  -->
    <script type="text/javascript" src="{{ URL::asset('js/daterangepicker.js') }}"></script>

</body>

</html>
