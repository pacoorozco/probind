@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('admin/level/title.level_delete') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('admin/level/title.level_delete') }}
    <small>{{ $level->name }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('admin-home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('admin/site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{ route('admin.levels.index') }}">
            {{ trans('admin/site.levels') }}
        </a>
    </li>
    <li class="active">
        {{ trans('admin/level/title.level_delete') }}
    </li>
    @endsection

    {{-- Content --}}
    @section('content')

            <!-- Notifications -->
    @include('partials.notifications')
            <!-- ./ notifications -->

    {{-- Delete Level Form --}}
    {!! Form::open(array('route' => array('admin.levels.destroy', $level), 'method' => 'delete', )) !!}
    @include('admin/level/_details', ['action' => 'delete'])
    {!! Form::close() !!}

@endsection