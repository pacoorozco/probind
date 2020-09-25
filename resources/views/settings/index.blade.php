@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('settings/title.settings_management') }} @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('settings/title.settings_management') }}
    <small>{{ __('settings/title.settings_management_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ __('site.dashboard') }}
        </a>
    </li>
    <li class="active">
        {{ __('site.settings') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('settings._form')

@endsection
