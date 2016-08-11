@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('settings/title.settings_management') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('settings/title.settings_management') }}
    <small>{{ trans('settings/title.settings_management_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li class="active">
        {{ trans('site.settings') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('settings._form')

@endsection
