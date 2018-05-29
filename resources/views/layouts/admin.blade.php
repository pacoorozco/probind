<!DOCTYPE html>
<html>
<!-- start: HEAD -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Administration Dashboard') :: ProBIND v3</title>
    <!-- start: META -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta content="ProBIND v3: Professional DNS Management - Administration" name="description">
    <meta content="Paco Orozco" name="author">
    @yield('meta')
    <!-- end: META -->
    <!-- start: GLOBAL CSS -->
    {!! HTML::style('themes/admin-lte/bootstrap/css/bootstrap.min.css') !!}
    {!! HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') !!}
    {!! HTML::style('//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
    <!-- end: GLOBAL CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @stack('styles')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    <!-- start: MAIN CSS -->
    {!! HTML::style('themes/admin-lte/dist/css/AdminLTE.min.css') !!}
    {!! HTML::style('themes/admin-lte/dist/css/skins/skin-blue.min.css') !!}
    {!! HTML::style('css/probind.css') !!}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    {!! HTML::script('//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') !!}
    {!! HTML::script('//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js') !!}
    <![endif]-->
    <!-- end: MAIN CSS -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
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
    {!! HTML::script('themes/admin-lte/plugins/jQuery/jquery-2.2.3.min.js') !!}
    {!! HTML::script('themes/admin-lte/bootstrap/js/bootstrap.min.js') !!}
    <!-- end: GLOBAL JAVASCRIPT -->
    <!-- start: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
    @stack('scripts')
    <!-- end: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
    <!-- start: MAIN JAVASCRIPT -->
    {!! HTML::script('themes/admin-lte/dist/js/app.min.js') !!}
    {!! HTML::script('js/probind.js') !!}
    <script>
        (function() {
            ProBIND.init();
        })();
    </script>
    <!-- end: MAIN JAVASCRIPT -->
</body>
<!-- end: BODY -->
</html>
