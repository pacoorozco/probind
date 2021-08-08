@extends('layouts.admin')

{{-- Web site Title --}}
@section('title', __('record/title.record_delete'))

{{-- Content Header --}}
@section('header')
    {{ __('record/title.record_delete') }}
    <small>{{ __('record/title.record_delete_subtitle', ['record' => $record->name]) }}</small>
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
        {{ __('record/title.record_delete') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    {{-- Delete zone Form --}}
    {!! Form::open(['route' => ['zones.records.destroy', $zone, $record], 'method' => 'delete']) !!}
    @include('record/_details', ['action' => 'delete'])
    {!! Form::close() !!}

@endsection
