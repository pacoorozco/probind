@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('record/title.view_records') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('record/title.view_records') }}
    <small>{{ __('record/title.view_records_subtitle', ['domain' => $zone->domain]) }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ __('site.dashboard') }}
        </a>
    </li>
    <li class="active">
        {{ __('record/title.view_records') }}
    </li>
@endsection


{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- actions -->
    <a href="{{ route('zones.records.create', $zone) }}" class="btn btn-success margin-bottom" role="button">
            <i class="fa fa-plus"></i> {{ __('record/title.create_new') }}
    </a>
    <!-- /.actions -->
    <div class="box">
        <div class="box-body">
            @include('record._table')
        </div>
    </div>
@endsection
