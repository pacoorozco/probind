@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('user/title.user_edit') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('user/title.user_edit') }}
    <small>{{ $user->hostname }}</small>
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
        {{ trans('user/title.user_edit') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->
    
    @include('user/_form')

@endsection
