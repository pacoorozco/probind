@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('server/title.server_management') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('server/title.server_management') }}
    <small>{{ trans('server/title.server_management_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li class="active">
        {{ trans('site.servers') }}
    </li>
@endsection


{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- actions -->
    <a href="{{ route('servers.create') }}" class="btn btn-success margin-bottom" role="button">
            <i class="fa fa-plus"></i> {{ trans('server/title.create_new') }}
    </a>
    <!-- /.actions -->
    <div class="box">
        <div class="box-body">
            @include('server._table')
        </div>
    </div>
@endsection
