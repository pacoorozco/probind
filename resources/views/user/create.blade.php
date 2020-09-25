@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('user/title.create_new') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('user/title.create_new') }}
    <small>{{ __('user/title.create_new_subtitle') }}</small>
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
        {{ __('user/title.create_new') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('user/_form')

@endsection
