@extends('layouts.installer')

@section('content')
    <!-- start: Database Form -->
    {!! Form::open(['route' => 'installDatabaseCreate']) !!}

    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <i class="fa fa-database"></i>
            <h3 class="box-title">{{ trans('installer.database.title') }}</h3>
        </div>
        <div class="box-body">
            <p>{{ trans('installer.database.sub-title') }}</p>

            <!-- dbname -->
            <div class="form-group {{ $errors->has('dbname') ? 'has-error' : '' }}">
                {!! Form::label('dbname', trans('installer.database.dbname-label'), array('class' => 'control-label required')) !!}
                <div class="controls">
                    <span class="help-block">{{ trans('installer.database.dbname-help') }}</span>
                    {!! Form::text('dbname', $dbname, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>
            <!-- ./ dbname -->

            <!-- username -->
            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                {!! Form::label('username', trans('installer.database.username-label'), array('class' => 'control-label required')) !!}
                <div class="controls">
                    <span class="help-block">{{ trans('installer.database.username-help') }}</span>
                    {!! Form::text('username', $username, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>
            <!-- ./ username -->

            <!-- password -->
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                {!! Form::label('password', trans('installer.database.password-label'), array('class' => 'control-label required')) !!}
                <div class="controls">
                    <span class="help-block">{{ trans('installer.database.password-help') }}</span>
                    {!! Form::text('password', $password, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>
            <!-- ./ password -->

            <!-- host -->
            <div class="form-group {{ $errors->has('host') ? 'has-error' : '' }}">
                {!! Form::label('host', trans('installer.database.host-label'), array('class' => 'control-label required')) !!}
                <div class="controls">
                    <span class="help-block">{{ trans('installer.database.host-help') }}</span>
                    {!! Form::text('host', $host, array('class' => 'form-control', 'required' => 'required')) !!}
                </div>
            </div>
            <!-- ./ host -->
        </div>

        <div class="box-footer">
            {!! Form::button(trans('general.next') . ' <i class="fa fa-arrow-right"></i>', array('type' => 'submit', 'class' => 'btn btn-primary pull-right', 'id' => 'submit')) !!}
        </div>
    </div>

    {!! Form::close() !!}
    <!-- end: Database Form -->
@endsection