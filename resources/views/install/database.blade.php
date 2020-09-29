@extends('layouts.installer')

{{-- Web site Title --}}
@section('title')
    {{ __('installer.database.title') }}
@endsection

@section('content')
    @include('partials.notifications')

    <!-- start: DATABASE FORM -->
    {!! Form::open(['route' => 'Installer::databaseSave']) !!}

    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <i class="fa fa-database"></i>
            <h3 class="box-title">{{ __('installer.database.header') }}</h3>
        </div>
        <div class="box-body">
            <p>{{ __('installer.database.sub-title') }}</p>

            <!-- dbtype -->
            <div class="form-group {{ $errors->has('dbtype') ? 'has-error' : '' }}">
                {!! Form::label('dbtype', __('installer.database.dbtype-label'), array('class' => 'control-label required')) !!}
                <div class="controls">
                    <span class="help-block">{{ __('installer.database.dbtype-help') }}</span>
                    {!! Form::select('dbtype', array('mysql' => 'MySQL Database'), null, ['class' => 'form-control', 'required' => 'required', 'disabled' => 'disabled']) !!}
                </div>
            </div>
            <!-- ./ dbtype -->

            <!-- dbname -->
            <div class="form-group {{ $errors->has('dbname') ? 'has-error' : '' }}">
                {!! Form::label('dbname', __('installer.database.dbname-label'), array('class' => 'control-label required')) !!}
                <div class="controls">
                    <span class="help-block">{{ __('installer.database.dbname-help') }}</span>
                    {!! Form::text('dbname', $dbname, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>
            <!-- ./ dbname -->

            <!-- username -->
            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                {!! Form::label('username', __('installer.database.username-label'), array('class' => 'control-label required')) !!}
                <div class="controls">
                    <span class="help-block">{{ __('installer.database.username-help') }}</span>
                    {!! Form::text('username', $username, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>
            <!-- ./ username -->

            <!-- password -->
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                {!! Form::label('password', __('installer.database.password-label'), array('class' => 'control-label required')) !!}
                <div class="controls">
                    <span class="help-block">{{ __('installer.database.password-help') }}</span>
                    {!! Form::text('password', $password, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>
            <!-- ./ password -->

            <!-- host -->
            <div class="form-group {{ $errors->has('host') ? 'has-error' : '' }}">
                {!! Form::label('host', __('installer.database.host-label'), array('class' => 'control-label required')) !!}
                <div class="controls">
                    <span class="help-block">{{ __('installer.database.host-help') }}</span>
                    {!! Form::text('host', $host, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>
            <!-- ./ host -->

            <!-- seed -->
            <div class="form-group {{ $errors->has('seed') ? 'has-error' : '' }}">
                <div class="checkbox">
                    <label class="control-label">
                        {{ Form::checkbox('seed', true, true, array('disabled' => 'disabled')) }}
                        {{ __('installer.database.seed-label') }}
                    </label>
                </div>
            </div>
            <!-- ./ seed -->
        </div>

        <div class="box-footer">
            {!! Form::button(__('general.next') . ' <i class="fa fa-arrow-right"></i>', array('type' => 'submit', 'class' => 'btn btn-primary pull-right', 'id' => 'submit')) !!}
        </div>
    </div>

    {!! Form::close() !!}
    <!-- end: DATABASE FORM -->
@endsection
