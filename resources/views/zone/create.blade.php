@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('zone/title.create_new') }} @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('zone/title.create_new') }}
    <small>{{ __('zone/title.create_new_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ __('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{ route('zones.index') }}">
            {{ __('site.zones') }}
        </a>
    </li>
    <li class="active">
        {{ __('zone/title.create_new') }}
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
