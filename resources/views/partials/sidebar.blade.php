<!-- start: MAIN NAVIGATION MENU -->
<ul class="sidebar-menu">
    <li class="header">ADMIN NAVIGATION</li>
    <li {!! (Request::is('home') ? ' class="active"' : '') !!}>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i><span>{{ trans('site.dashboard') }}</span>
        </a>
    </li>
</ul>
<!-- end: MAIN NAVIGATION MENU -->