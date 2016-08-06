<!-- start: MAIN NAVIGATION MENU -->
<ul class="sidebar-menu">
    <li class="header">{{ trans('site.navigation') }}</li>
    <li {!! (Request::is('home') ? ' class="active"' : '') !!}>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i><span>{{ trans('site.dashboard') }}</span>
        </a>
    </li>

    <li {!! (Request::is('servers') ? ' class="active"' : '') !!}>
        <a href="{{ route('servers.index') }}">
            <i class="fa fa-server"></i><span>{{ trans('site.servers') }}</span>
        </a>
    </li>
</ul>
<!-- end: MAIN NAVIGATION MENU -->