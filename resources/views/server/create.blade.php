@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('server/title.create_new') }} @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('server/title.create_new') }}
    <small>{{ __('server/title.create_new_subtitle') }}</small>
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
        {{ __('server/title.create_new') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('server/_form')

@endsection
