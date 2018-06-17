@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('zone/title.zone_management') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('zone/title.zone_management') }}
    <small>{{ trans('zone/title.zone_management_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li class="active">
        {{ trans('site.zones') }}
    </li>
@endsection


{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- actions -->
    <a href="{{ route('zones.create') }}" class="btn btn-success margin-bottom" role="button">
            <i class="fa fa-plus"></i> {{ trans('zone/title.create_new') }}
    </a>
    <!-- /.actions -->
    <div class="box">
        <div class="box-body">
            @include('zone._table')
        </div>
    </div>
@endsection
