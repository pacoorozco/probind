{{-- Create / Edit Server Form --}}
@if (isset($server))
    {!! Form::model($server, ['route' => ['servers.update', $server], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'servers.store']) !!}
@endif

<div class="box box-solid">
    <div class="box-body">

        <!-- hostname -->
        <div class="form-group {{ $errors->has('hostname') ? 'has-error' : '' }}">
            {!! Form::label('hostname', __('server/model.hostname'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::text('hostname', null, array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ $errors->first('hostname', ':message') }}</span>
            </div>
        </div>
        <!-- ./ hostname -->

        <!-- ip_address -->
        <div class="form-group {{ $errors->has('ip_address') ? 'has-error' : '' }}">
            {!! Form::label('ip_address', __('server/model.ip_address'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::text('ip_address', null, array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ $errors->first('ip_address', ':message') }}</span>
            </div>
        </div>
        <!-- ./ ip_address -->

        <!-- type -->
        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
            {!! Form::label('type', __('server/model.type'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::select('type', array('master' => __('server/model.types.master'), 'slave' => __('server/model.types.slave')), null, array('class' => 'form-control', 'required' => 'required')) !!}
                {{ $errors->first('type', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ type -->

        <!-- ns_record -->
        <div class="form-group {{ $errors->has('ns_record') ? 'has-error' : '' }}">
            {!! Form::label('ns_record', __('server/model.ns_record'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::select('ns_record', array('1' => __('general.yes'), '0' => __('general.no')), null, array('class' => 'form-control', 'required' => 'required')) !!}
                {{ $errors->first('ns_record', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ ns_record -->

        <!-- push_updates -->
        <div class="form-group {{ $errors->has('push_updates') ? 'has-error' : '' }}">
            {!! Form::label('push_updates', __('server/model.push_updates'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::select('push_updates', array('1' => __('general.yes'), '0' => __('general.no')), null, array('class' => 'form-control', 'required' => 'required')) !!}
                {{ $errors->first('push_updates', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ push_updates -->

        <!-- active -->
        <div class="form-group {{ $errors->has('active') ? 'has-error' : '' }}">
            {!! Form::label('active', __('server/model.active'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::select('active', array('1' => __('general.yes'), '0' => __('general.no')), null, array('class' => 'form-control', 'required' => 'required')) !!}
                {{ $errors->first('active', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ active -->

    </div>

    <div class="box-footer">
        <!-- Form Actions -->
        <a href="{{ route('servers.index') }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
        </a>
    {!! Form::button('<i class="fa fa-floppy-o"></i> ' . __('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
    <!-- ./ form actions -->
    </div>
</div>
{!! Form::close() !!}
