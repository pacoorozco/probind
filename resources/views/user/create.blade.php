@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('user/title.create_new') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('user/title.create_new') }}
    <small>{{ trans('user/title.create_new_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{ route('users.index') }}">
            {{ trans('site.users') }}
        </a>
    </li>
    <li class="active">
        {{ trans('user/title.create_new') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('user/_form')

@endsection
