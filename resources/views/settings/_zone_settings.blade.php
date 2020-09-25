{{-- Zone global settings panel --}}

<h4>{{ __('settings/title.zone_settings') }}</h4>

<p>{{ __('settings/messages.zone_defaults_information') }}</p>

<!-- zone_default_mname -->
<div class="form-group {{ $errors->has('zone_default_mname') ? 'has-error' : '' }}">
    {!! Form::label('zone_default_mname', __('settings/model.zone_default_mname'), array('class' => 'control-label required')) !!}
    <div class="controls">
        {!! Form::text('zone_default_mname', setting('zone_default_mname'), array('class' => 'form-control', 'required' => 'required')) !!}
        <span class="help-block">{{ __('settings/model.zone_default_mname_help') }}</span>
        <span class="help-block">{{ $errors->first('zone_default_mname', ':message') }}</span>
    </div>
</div>
<!-- ./ zone_default_mname  -->

<!-- zone_default_rname -->
<div class="form-group {{ $errors->has('zone_default_rname') ? 'has-error' : '' }}">
    {!! Form::label('zone_default_rname', __('settings/model.zone_default_rname'), array('class' => 'control-label required')) !!}
    <div class="controls">
        {!! Form::email('zone_default_rname', setting('zone_default_rname'), array('class' => 'form-control', 'required' => 'required')) !!}
        <span class="help-block">{{ __('settings/model.zone_default_rname_help') }}</span>
        <span class="help-block">{{ $errors->first('zone_default_rname', ':message') }}</span>
    </div>
</div>
<!-- ./ zone_default_rname  -->

<div class="row">
    <div class="col-md-6">
        <!-- zone_default_refresh -->
        <div class="form-group {{ $errors->has('zone_default_refresh') ? 'has-error' : '' }}">
            {!! Form::label('zone_default_refresh', __('settings/model.zone_default_refresh'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::number('zone_default_refresh', setting('zone_default_refresh'), array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ __('settings/model.zone_default_refresh_help') }}</span>
                <span class="help-block">{{ $errors->first('zone_default_refresh', ':message') }}</span>
            </div>
        </div>
        <!-- ./ zone_default_refresh -->
    </div>
    <div class="col-md-6">
        <!-- zone_default_retry -->
        <div class="form-group {{ $errors->has('zone_default_retry') ? 'has-error' : '' }}">
            {!! Form::label('zone_default_retry', __('settings/model.zone_default_retry'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::number('zone_default_retry', setting('zone_default_retry'), array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ __('settings/model.zone_default_retry_help') }}</span>
                <span class="help-block">{{ $errors->first('zone_default_retry', ':message') }}</span>
            </div>
        </div>
        <!-- ./ zone_default_retry -->
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- zone_default_expire -->
        <div class="form-group {{ $errors->has('zone_default_expire') ? 'has-error' : '' }}">
            {!! Form::label('zone_default_expire', __('settings/model.zone_default_expire'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::number('zone_default_expire', setting('zone_default_expire'), array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ __('settings/model.zone_default_expire_help') }}</span>
                <span class="help-block">{{ $errors->first('zone_default_expire', ':message') }}</span>
            </div>
        </div>
        <!-- ./ zone_default_expire -->
    </div>
    <div class="col-md-6">
        <!-- zone_default_negative_ttl -->
        <div class="form-group {{ $errors->has('zone_default_negative_ttl') ? 'has-error' : '' }}">
            {!! Form::label('zone_default_negative_ttl', __('settings/model.zone_default_negative_ttl'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::number('zone_default_negative_ttl', setting('zone_default_negative_ttl'), array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ __('settings/model.zone_default_negative_ttl_help') }}</span>
                <span class="help-block">{{ $errors->first('zone_default_negative_ttl', ':message') }}</span>
            </div>
        </div>
        <!-- ./ zone_default_negative_ttl -->
    </div>
</div>

<!-- zone_default_default_ttl -->
<div class="form-group {{ $errors->has('zone_default_default_ttl') ? 'has-error' : '' }}">
    {!! Form::label('zone_default_default_ttl', __('settings/model.zone_default_default_ttl'), array('class' => 'control-label required')) !!}
    <div class="controls">
        {!! Form::number('zone_default_default_ttl', setting('zone_default_default_ttl'), array('class' => 'form-control', 'required' => 'required')) !!}
        <span class="help-block">{{ __('settings/model.zone_default_default_ttl_help') }}</span>
        <span class="help-block">{{ $errors->first('zone_default_default_ttl', ':message') }}</span>
    </div>
</div>
<!-- ./ zone_default_default_ttl -->
