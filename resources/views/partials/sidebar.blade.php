<!-- start: MAIN NAVIGATION MENU -->
<ul class="sidebar-menu">
    <li class="header">{{ trans('site.navigation') }}</li>
    <li {!! (Request::is('home') ? ' class="active"' : '') !!}>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i><span>{{ trans('site.dashboard') }}</span>
        </a>
    </li>
    <li {!! (Request::is('servers*') ? ' class="active"' : '') !!}>
        <a href="{{ route('servers.index') }}">
            <i class="fa fa-server"></i><span>{{ trans('site.servers') }}</span>
        </a>
    </li>
    <li {!! (Request::is('zones*') ? ' class="active"' : '') !!}>
        <a href="{{ route('zones.index') }}">
            <i class="fa fa-database"></i><span>{{ trans('site.zones') }}</span>
        </a>
    </li>
    <li {!! (Request::is('search*') ? ' class="active"' : '') !!}>
        <a href="{{ route('search.index') }}">
            <i class="fa fa-search"></i><span>{{ trans('site.search') }}</span>
        </a>
    </li>
    <li {!! (Request::is('push') ? ' class="active"' : '') !!}>
        <a href="{{ route('tools.view_updates') }}">
            <i class="fa fa-download"></i><span>{{ trans('site.push_updates') }}</span>
        </a>
    </li>

    <li class="{!! (Request::is('tools*') ? 'treeview active' : 'treeview') !!}">
        <a href="#">
            <i class="fa fa-wrench"></i><span>{{ trans('site.tools') }}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {!! (Request::is('tools/update') ? ' class="active"' : '') !!}>
                <a href="{{ route('tools.bulk_update') }}">
                    <i class="fa fa-circle-o"></i> {{ trans('site.bulk_update') }}
                </a>
            </li>
            <li {!! (Request::is('tools/push') ? ' class="active"' : '') !!}>
                <a href="{{ route('tools.view_updates') }}">
                    <i class="fa fa-circle-o"></i> {{ trans('site.push_updates') }}
                </a>
            </li>
            <li {!! (Request::is('tools/import') ? ' class="active"' : '') !!}>
                <a href="{{ route('tools.import_zone') }}">
                    <i class="fa fa-circle-o"></i> {{ trans('site.import_zone') }}
                </a>
            </li>
        </ul>
    </li>

    <li class="header">{{ trans('site.configure') }}</li>
    <li {!! (Request::is('users*') ? ' class="active"' : '') !!}>
        <a href="{{-- route('users.index') --}}">
            <i class="fa fa-users"></i><span>{{ trans('site.users') }}</span>
        </a>
    </li>
    <li {!! (Request::is('settings*') ? ' class="active"' : '') !!}>
        <a href="{{ route('settings.index') }}">
            <i class="fa fa-gears"></i><span>{{ trans('site.settings') }}</span>
        </a>
    </li>

</ul>
<!-- end: MAIN NAVIGATION MENU -->
