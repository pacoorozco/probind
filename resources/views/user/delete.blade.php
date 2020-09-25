@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('user/title.user_delete') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('user/title.user_delete') }}
    <small>{{ $user->username }}</small>
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
        {{ __('user/title.user_delete') }}
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
