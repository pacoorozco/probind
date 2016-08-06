@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('server/title.server_delete') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('server/title.server_delete') }}
    <small>{{ $server->hostname }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{ route('servers.index') }}">
            {{ trans('site.servers') }}
        </a>
    </li>
    <li class="active">
        {{ trans('server/title.server_delete') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    {{-- Delete server Form --}}
    {!! Form::open(array('route' => array('servers.destroy', $server), 'method' => 'delete', )) !!}
    @include('server/_details', ['action' => 'delete'])
    {!! Form::close() !!}

@endsection