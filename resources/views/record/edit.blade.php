@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('record/title.record_edit') }} @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('record/title.record_edit') }}
    <small>{{ __('record/title.record_edit_subtitle', ['record' => $record->name]) }}</small>
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
            {{ __('site.zones') }}
        </a>
    </li>
    <li class="active">
        {{ __('record/title.record_edit') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('record/_form')

@endsection
