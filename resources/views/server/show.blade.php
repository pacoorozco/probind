@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('server/title.server_show') }} @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('server/title.server_show') }}
    <small>{{ __('server/title.server_show_subtitle', ['server' => $server->hostname]) }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ __('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{ route('servers.index') }}">
            {{ __('site.servers') }}
        </a>
    </li>
    <li class="active">
        {{ __('server/title.server_show') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('server/_details', ['action' => 'show'])

@endsection
