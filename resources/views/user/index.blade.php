@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('user/title.user_management') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('user/title.user_management') }}
    <small>{{ trans('user/title.user_management_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li class="active">
        {{ trans('site.users') }}
    </li>
@endsection


{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- actions -->
    <a href="{{ route('users.create') }}" class="btn btn-success margin-bottom" role="button">
            <i class="fa fa-plus"></i> {{ trans('user/title.create_new') }}
    </a>
    <!-- /.actions -->
    <div class="box">
        <div class="box-body">
            @include('user._table')
        </div>
    </div>
@endsection
