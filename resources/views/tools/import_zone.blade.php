@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('tools/title.import_zone') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('tools/title.import_zone') }}
    <small>{{ trans('tools/title.import_zone_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{-- route('tools.index') --}}">
            {{ trans('site.tools') }}
        </a>
    </li>
    <li class="active">
        {{ trans('tools/title.import_zone') }}
    </li>
@endsection

{{-- Content --}}
@section('content')
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('tools/title.import_zone') }}</h3>
        </div>

        {{-- Import Zone Form --}}
        {!! Form::open(['route' => 'tools.import_zone_post', 'files' => true]) !!}
        <div class="box box-solid">
            <div class="box-body">

                <!-- domain -->
                <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                    {!! Form::label('domain', trans('zone/model.domain'), array('class' => 'control-label required')) !!}
                    <div class="controls">
                        {!! Form::text('domain', null, array('class' => 'form-control', 'required' => 'required')) !!}
                        <span class="help-block">{{ $errors->first('domain', ':message') }}</span>
                    </div>
                </div>
                <!-- ./ domain -->

                <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                    {!! Form::label('zonefile', trans('tools/messages.zonefile'), array('class' => 'control-label required')) !!}
                    <div class="controls">
                        {!! Form::file('zonefile', array('required' => 'required')) !!}
                        <span class="help-block">{{ $errors->first('domain', ':message') }}</span>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <!-- Form Actions -->
                <a href="{{ route('home') }}" class="btn btn-primary" role="button">
                        <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
                </a>
            {!! Form::button('<i class="fa fa-upload"></i> ' . trans('tools/messages.import_button'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            <!-- ./ form actions -->
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
