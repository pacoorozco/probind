@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('record/title.create_new') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('record/title.create_new') }}
    <small>{{ __('record/title.create_new_subtitle', ['domain' => $zone->domain]) }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ __('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{ route('zones.records.index', $zone) }}">
            {{ __('site.records') }}
        </a>
    </li>
    <li class="active">
        {{ __('record/title.create_new') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('record/_form')

@endsection
