<!DOCTYPE html>
<html lang="en">
<!-- start: HEAD -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Administration Dashboard') :: ProBIND v3</title>
    <!-- start: META -->
    <meta content="ProBIND v3: Professional DNS Management - Administration" name="description">
    <meta content="Paco Orozco" name="author">
    @yield('meta')
    <!-- end: META -->
    <!-- start: GLOBAL CSS -->
    <link rel="stylesheet" href="{{ mix('css/vendor.css') }}">
    <link rel="stylesheet" href="{{ mix('css/theme.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js">
    <link rel="stylesheet" href="//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js">
    <![endif]-->
    <!-- end: GLOBAL CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @stack('styles')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
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
    <script src="{{ mix('js/vendor.js') }} "></script>
    <script src="{{ mix('js/theme.js') }} "></script>
    <!-- end: GLOBAL JAVASCRIPT -->
    <!-- start: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
    @stack('scripts')
    <!-- end: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
</body>
<!-- end: BODY -->
</html>
