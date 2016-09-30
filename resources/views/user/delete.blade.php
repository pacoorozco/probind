@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('user/title.user_delete') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('user/title.user_delete') }}
    <small>{{ $user->username }}</small>
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
        {{ trans('user/title.user_delete') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    {{-- Delete user Form --}}
    {!! Form::open(array('route' => array('users.destroy', $user), 'method' => 'delete', )) !!}
    @include('user/_details', ['action' => 'delete'])
    {!! Form::close() !!}

@endsection
