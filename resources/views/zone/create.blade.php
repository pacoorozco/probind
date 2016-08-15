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

    <div class="row">
        <div class="col-md-6">
            @include('zone/_form_master')
        </div>
        <div class="col-md-6">
            @include('zone/_form_slave')
        </div>
    </div>
@endsection
