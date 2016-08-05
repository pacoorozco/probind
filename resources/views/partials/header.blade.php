<header class="main-header">

    <!-- start: LOGO -->
    <a href="{{ route('home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>pB</b>v3</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>ProBIND</b> v3</span>
    </a>
    <!-- end: LOGO -->

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- start: RESPONSIVE MENU TOGGLER -->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
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
                        <img src="{{-- auth()->user()->profile->avatar->url() --}}" class="user-image"
                             alt="{{ trans('user/profile.avatar') }}"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{-- auth()->user()->name --}} username</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{-- auth()->user()->profile->avatar->url() --}}" class="img-circle"
                                 alt="{{ trans('user/profile.avatar') }}"/>
                            <p>
                                {{-- auth()->user()->name --}} username - Admin
                                <small>Member since {{-- date("M Y", strtotime(auth()->user()->created_at)) --}}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-12 text-center">
                                <a href="#">{{ trans('site.my_achievements') }}</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{-- route('profiles.show', Auth::user()->username) --}}" class="btn btn-default btn-flat">
                                    {{ trans('site.my_profile') }}
                                </a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url('auth/logout') }}" class="btn btn-default btn-flat">
                                    {{ trans('general.logout') }}
                                </a>
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

