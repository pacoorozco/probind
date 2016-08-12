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

    <div class="row">
        <div class="col-md-8">
            @if ($zone->isMasterZone())
                @include('zone/_details_master')
            @else
                @include('zone/_details_slave')
            @endif
        </div>
        <div class="col-md-4">
            @include('zone/_delete')
        </div>
    </div>
@endsection