@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('zone/title.zone_edit') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('zone/title.zone_edit') }}
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
        {{ trans('zone/title.zone_edit') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @if ($zone->isMasterZone())
        @include('zone._form_master')
    @else
        @include('zone._form_slave')
    @endif

@endsection
