<header class="main-header">

    <!-- start: LOGO -->
    <a href="{{ route('home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>pB</b>3</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>ProBIND</b>3</span>
    </a>
    <!-- end: LOGO -->

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- start: RESPONSIVE MENU TOGGLER -->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- end: RESPONSIVE MENU TOGGLER -->
        <div class="navbar-custom-menu">
            <!-- start: TOP NAVIGATION MENU -->
            <ul class="nav navbar-nav">

                <!-- start: NOTIFICATION DROPDOWN -->
                <!-- TODO -->
                <!-- end: NOTIFICATION DROPDOWN -->

                <!-- start: USER DROPDOWN -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ asset('images/missing_profile.png') }}" class="user-image"
                             alt="{{ __('user/profile.avatar') }}"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ asset('images/missing_profile.png') }}" class="img-circle"
                                 alt="{{ __('user/profile.avatar') }}"/>
                            <p>
                                {{ auth()->user()->name }} - Admin
                                <small>Member since {{ auth()->user()->created_at->format('M Y') }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                {!! Form::open(['url' => '/logout']) !!}
                                {!! Form::button(__('auth.logout'), ['type' => 'submit', 'class' => 'btn btn-default btn-flat']) !!}
                                {!! Form::close() !!}
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- end: USER DROPDOWN -->
            </ul>
            <!-- end: TOP NAVIGATION MENU -->
        </div>
    </nav>
</header>
