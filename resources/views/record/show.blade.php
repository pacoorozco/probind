@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('record/title.record_show') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('record/title.record_show') }}
    <small>{{ trans('record/title.record_show_subtitle', ['record' => $record->name]) }}</small>
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
            {{ trans('site.records') }}
        </a>
    </li>
    <li class="active">
        {{ trans('record/title.record_show') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('record/_details', ['action' => 'show'])

@endsection
