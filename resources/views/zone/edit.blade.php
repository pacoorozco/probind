@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('zone/title.zone_edit') }} @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('zone/title.zone_edit') }}
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
        {{ __('zone/title.zone_edit') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @if ($zone->isPrimary())
        @include('zone._form_master')
    @else
        @include('zone._form_slave')
    @endif

@endsection
