@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('user/title.user_show') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('user/title.user_show') }}
    <small>{{ __('user/title.user_show_subtitle', ['user' => $user->username]) }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ __('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{ route('users.index') }}">
            {{ __('site.users') }}
        </a>
    </li>
    <li class="active">
        {{ __('user/title.user_show') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('user/_details', ['action' => 'show'])

@endsection
