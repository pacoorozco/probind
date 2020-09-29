@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('zone/title.zone_show') }} @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('zone/title.zone_show') }}
    <small>{{ $zone->present()->domain }}</small>
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
        {{ __('zone/title.zone_show') }}
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
            @includeWhen($zone->isMasterZone(), 'zone/_resource_records')
            @include('zone/_delete')
        </div>
    </div>
@endsection
