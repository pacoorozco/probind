@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('zone/title.zone_show') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('zone/title.zone_show') }}
    <small>{{ $zone->domain }}</small>
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
        {{ trans('zone/title.zone_show') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('record/_details', ['action' => 'show'])

@endsection