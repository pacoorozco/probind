@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('zone/title.create_new') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('zone/title.create_new') }}
    <small>{{ trans('zone/title.create_new_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{ route('zones.index') }}">
            {{ trans('site.zones') }}
        </a>
    </li>
    <li class="active">
        {{ trans('zone/title.create_new') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('zone/_form')

@endsection