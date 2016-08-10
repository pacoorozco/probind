@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('record/title.record_edit') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('record/title.record_edit') }}
    <small>{{ trans('record/title.record_edit_subtitle', ['record' => $record->name]) }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{ route('zones.records.index', $zone) }}">
            {{ trans('site.zones') }}
        </a>
    </li>
    <li class="active">
        {{ trans('record/title.record_edit') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->
    
    @include('record/_form')

@endsection