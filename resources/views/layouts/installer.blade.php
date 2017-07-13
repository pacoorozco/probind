<!DOCTYPE html>
<html>
<!-- start: HEAD -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Installer') :: ProBIND v3</title>
    <!-- start: META -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta content="ProBIND v3: Professional DNS Management - Login" name="description">
    <meta content="Paco Orozco" name="author">
@yield('meta')
<!-- end: META -->
    <!-- start: GLOBAL CSS -->
{!! HTML::style('vendor/AdminLTE/bootstrap/css/bootstrap.min.css') !!}
{!! HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') !!}
{!! HTML::style('//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
<!-- end: GLOBAL CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
@stack('styles')
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    <!-- start: MAIN CSS -->
{!! HTML::style('vendor/AdminLTE/dist/css/AdminLTE.min.css') !!}
{!! HTML::style('vendor/AdminLTE/dist/css/skins/skin-blue.min.css') !!}
{!! HTML::style('css/probind.css') !!}
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    {!! HTML::script('//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') !!}
    {!! HTML::script('//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js') !!}
    <![endif]-->
    <!-- end: MAIN CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
@yield('styles')
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
</head>
<!-- start: BODY -->
<body class="hold-transition skin-blue layout-top-nav">
<!-- start: MAIN CONTAINER -->
<div class="wrapper">

    <!-- start: PAGE -->
    <div class="content-wrapper">
        <!-- start: PAGE CONTENT -->
        <section class="content">
            <div class="row">
                <div class="container col-md-6 col-md-offset-3">
                    <div class="register-logo">
                        <a href="{{ route('home') }}"><b>ProBIND</b> v3</a>
                    </div>
                    @yield('content')
                </div>
            </div>
        </section>
        <!-- end: PAGE CONTENT-->
    </div>
    <!-- end: PAGE -->

    <!-- start: FOOTER -->
@include('partials.footer')
<!-- end: FOOTER -->
</div>
<!-- end: MAIN CONTAINER -->
<!-- start: GLOBAL JAVASCRIPT -->
{!! HTML::script('vendor/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js') !!}
{!! HTML::script('vendor/AdminLTE/bootstrap/js/bootstrap.min.js') !!}
<!-- end: GLOBAL JAVASCRIPT -->
<!-- start: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
@stack('scripts')
<!-- end: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
<!-- start: MAIN JAVASCRIPT -->
{!! HTML::script('vendor/AdminLTE/dist/js/app.min.js') !!}
<!-- end: MAIN JAVASCRIPT -->
</body>
<!-- end: BODY -->
</html>
