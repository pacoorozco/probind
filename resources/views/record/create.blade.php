@extends('layouts.admin')

{{-- Web site Title --}}
@section('title', __('record/title.create_new'))

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

    {!! Form::open(['route' => ['zones.records.store', $zone]]) !!}

    <div class="box box-solid">
        <div class="box-body">

            <!-- zone -->
            <div class="form-group">
                {!! Form::label('zone', __('zone/model.domain'), ['class' => 'control-label']) !!}
                <div class="controls">
                    {{ $zone->domain }}
                </div>
            </div>
            <!-- ./ zone -->

            <!-- name -->
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                {!! Form::label('name', __('record/model.name'), ['class' => 'control-label required']) !!}
                <div class="controls">
                    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    <span class="help-block">
                    {{ __('record/model.name_help') }}
                        {{ $errors->first('name', ':message') }}
                </span>
                </div>
            </div>
            <!-- ./ name -->

            <!-- type -->
            <div class="form-group">
                {!! Form::label('type', __('record/model.type'), ['class' => 'control-label required']) !!}
                <div class="controls">
                    {!! Form::select('type', $zone->getValidRecordTypesForThisZone(), $zone->getDefaultRecordType(), ['class' => 'form-control']) !!}
                    <span class="help-block"></span>
                </div>
            </div>
            <!-- ./ type -->

            <!-- data -->
            <div class="form-group {{ $errors->has('data') ? 'has-error' : '' }}">
                {!! Form::label('data', __('record/model.data'), ['class' => 'control-label required']) !!}
                <div class="controls">
                    {!! Form::text('data', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    <span class="help-block">{{ $errors->first('data', ':message') }}</span>
                </div>
            </div>
            <!-- ./ data -->

            <!-- ttl -->
            <div class="form-group {{ $errors->has('ttl') ? 'has-error' : '' }}">
                {!! Form::label('ttl', __('record/model.ttl'), ['class' => 'control-label']) !!}
                <div class="controls">
                    {!! Form::number('ttl', null, ['class' => 'form-control']) !!}
                    <span class="help-block">
                    {{ __('record/model.ttl_help') }}
                        {{ $errors->first('ttl', ':message') }}
                </span>
                </div>
            </div>
            <!-- ./ ttl -->

            <a class="btn btn-default" role="button" data-toggle="collapse" href="#collapseHelp" aria-expanded="false" aria-controls="collapseHelp">
                {{ __('general.help') }}
            </a>

        </div>

        <div class="box-footer">
            <!-- Form Actions -->
            <a href="{{ route('zones.records.index', $zone) }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
            </a>
        {!! Form::button('<i class="fa fa-floppy-o"></i> ' . __('general.save'), ['type' => 'submit', 'class' => 'btn btn-success']) !!}
        <!-- ./ form actions -->
        </div>
    </div>
    {!! Form::close() !!}

    <div id="collapseHelp" class="collapse">
        <pre>{!! __('record/messages.records_help') !!}</pre>
    </div>
@endsection
