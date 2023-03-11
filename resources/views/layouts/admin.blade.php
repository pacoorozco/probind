<!DOCTYPE html>
<html lang="en">
<!-- start: HEAD -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Administration Dashboard') :: ProBIND3</title>
    <!-- start: META -->
    <meta content="ProBIND3: Professional DNS Management - Administration" name="description">
    <meta content="Paco Orozco" name="author">
    @yield('meta')
    <!-- end: META -->
    <!-- start: GLOBAL CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
    <!-- end: GLOBAL CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @stack('styles')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    <!-- start: MAIN CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/probind.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js">
    <link rel="stylesheet" href="//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js">
    <![endif]-->
    <!-- end: MAIN CSS -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>
<!-- end: HEAD -->
<!-- start: BODY -->
<body class="skin-blue sidebar-mini">
    <!-- start: MAIN CONTAINER -->
    <div class="wrapper">

        <!-- start: HEADER -->
        @include('partials.header')
        <!-- end: HEADER -->

        <!-- start: SIDEBAR -->
        <aside class="main-sidebar">
            <section class="sidebar">
                @include('partials.sidebar')
            </section>
        </aside>
        <!-- end: SIDEBAR -->

        <!-- start: PAGE -->
        <div class="content-wrapper">
            <!-- start: PAGE HEADER -->
            <section class="content-header">
                <!-- start: PAGE TITLE & BREADCRUMB -->
                <h1>
                    @yield('header', 'Title <small>page description</small>')
                </h1>
                <ol class="breadcrumb">
                    @yield('breadcrumbs')
                </ol>
                <!-- end: PAGE TITLE & BREADCRUMB -->
            </section>
            <!-- end: PAGE HEADER -->

            <!-- start: PAGE CONTENT -->
            <section class="content">
                @yield('content')
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
    <script src="{{ asset('vendor/AdminLTE/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/AdminLTE/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- end: GLOBAL JAVASCRIPT -->
    <!-- start: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
    @stack('scripts')
    <!-- end: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
    <!-- start: MAIN JAVASCRIPT -->
    <script src="{{ asset('vendor/AdminLTE/js/adminlte.min.js') }}"></script>
    <!-- end: MAIN JAVASCRIPT -->
</body>
<!-- end: BODY -->
</html>
