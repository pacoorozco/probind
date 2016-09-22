{{-- SSH global settings panel --}}

<h4>{{ trans('settings/title.ssh_settings_credentials') }}</h4>

<!-- ssh_default_user -->
<div class="form-group {{ $errors->has('ssh_default_user') ? 'has-error' : '' }}">
    {!! Form::label('ssh_default_user', trans('settings/model.ssh_default_user'), array('class' => 'control-label required')) !!}
    <div class="controls">
        {!! Form::text('ssh_default_user', Setting::get('ssh_default_user'), array('class' => 'form-control', 'required' => 'required')) !!}
        <span class="help-block">{{ trans('settings/model.ssh_default_user_help') }}</span>
        <span class="help-block">{{ $errors->first('ssh_default_user', ':message') }}</span>
    </div>
</div>
<!-- ./ ssh_default_user  -->

<!-- ssh_default_key -->
<div class="form-group {{ $errors->has('ssh_default_key') ? 'has-error' : '' }}">
    {!! Form::label('ssh_default_key', trans('settings/model.ssh_default_key'), array('class' => 'control-label required')) !!}
    <div class="controls">
        {!! Form::textarea('ssh_default_key', Setting::get('ssh_default_key'), array('class' => 'form-control', 'required' => 'required')) !!}
        <span class="help-block">{{ trans('settings/model.ssh_default_key_help') }}</span>
        <span class="help-block">{{ $errors->first('ssh_default_key', ':message') }}</span>
    </div>
</div>
<!-- ./ ssh_default_key  -->

<h4>{{ trans('settings/title.ssh_settings_configuration') }}</h4>

<!-- ssh_default_port -->
<div class="form-group {{ $errors->has('ssh_default_port') ? 'has-error' : '' }}">
    {!! Form::label('ssh_default_port', trans('settings/model.ssh_default_port'), array('class' => 'control-label required')) !!}
    <div class="controls">
        {!! Form::number('ssh_default_port', Setting::get('ssh_default_port'), array('class' => 'form-control', 'required' => 'required')) !!}
        <span class="help-block">{{ trans('settings/model.ssh_default_port_help') }}</span>
        <span class="help-block">{{ $errors->first('ssh_default_port', ':message') }}</span>
    </div>
</div>
<!-- ./ ssh_default_port  -->

<!-- ssh_default_remote_path -->
<div class="form-group {{ $errors->has('ssh_default_remote_path') ? 'has-error' : '' }}">
    {!! Form::label('ssh_default_remote_path', trans('settings/model.ssh_default_remote_path'), array('class' => 'control-label required')) !!}
    <div class="controls">
        {!! Form::text('ssh_default_remote_path', Setting::get('ssh_default_remote_path'), array('class' => 'form-control', 'required' => 'required')) !!}
        <span class="help-block">{{ trans('settings/model.ssh_default_remote_path_help') }}</span>
        <span class="help-block">{{ $errors->first('ssh_default_remote_path', ':message') }}</span>
    </div>
</div>
<!-- ./ ssh_default_remote_path  -->
