{{-- Edit Settings Form --}}
{!! Form::open(['route' => ['settings.update'], 'method' => 'put']) !!}


<div class="box">
    <div class="box-body">
        <div class="nav-tabs-custom">

            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#panel_settings_tab1" data-toggle="tab">
                        Zone's defaults
                    </a>
                </li>
                <li>
                    <a href="#panel_settings_tab2" data-toggle="tab">
                        RR's defaults
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                <!-- Zone's defaults settings -->
                <div class="tab-pane active" id="panel_settings_tab1">

                    <p>{{ trans('settings/messages.zone_defaults_information') }}</p>

                    <!-- zone_default.mname -->
                    <div class="form-group {{ $errors->has('zone_default.mname') ? 'has-error' : '' }}">
                        {!! Form::label('zone_default.mname', trans('settings/model.zone_default.mname'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::text('zone_default.mname', Registry::get('zone_default.mname'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ trans('settings/model.zone_default.mname_help') }}</span>
                            <span class="help-block">{{ $errors->first('zone_default.mname', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ zone_default.mname  -->

                    <!-- zone_default.rname -->
                    <div class="form-group {{ $errors->has('zone_default.rname') ? 'has-error' : '' }}">
                        {!! Form::label('zone_default.rname', trans('settings/model.zone_default.rname'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::email('zone_default.rname', Registry::get('zone_default.rname'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ trans('settings/model.zone_default.rname_help') }}</span>
                            <span class="help-block">{{ $errors->first('zone_default.rname', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ zone_default.rname  -->

                    <!-- zone_default.refresh -->
                    <div class="form-group {{ $errors->has('zone_default.refresh') ? 'has-error' : '' }}">
                        {!! Form::label('zone_default.refresh', trans('settings/model.zone_default.refresh'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::number('zone_default.refresh', Registry::get('zone_default.refresh'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ trans('settings/model.zone_default.refresh_help') }}</span>
                            <span class="help-block">{{ $errors->first('zone_default.refresh', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ zone_default.refresh -->

                    <!-- zone_default.retry -->
                    <div class="form-group {{ $errors->has('zone_default.retry') ? 'has-error' : '' }}">
                        {!! Form::label('zone_default.retry', trans('settings/model.zone_default.retry'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::number('zone_default.retry', Registry::get('zone_default.retry'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ trans('settings/model.zone_default.retry_help') }}</span>
                            <span class="help-block">{{ $errors->first('zone_default.retry', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ zone_default.retry -->

                    <!-- zone_default.expire -->
                    <div class="form-group {{ $errors->has('zone_default.expire') ? 'has-error' : '' }}">
                        {!! Form::label('zone_default.expire', trans('settings/model.zone_default.expire'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::number('zone_default.expire', Registry::get('zone_default.expire'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ trans('settings/model.zone_default.expire_help') }}</span>
                            <span class="help-block">{{ $errors->first('zone_default.expire', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ zone_default.expire -->

                    <!-- zone_default.minimum_ttl -->
                    <div class="form-group {{ $errors->has('zone_default.minimum_ttl') ? 'has-error' : '' }}">
                        {!! Form::label('zone_default.minimum_ttl', trans('settings/model.zone_default.minimum_ttl'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::number('zone_default.minimum_ttl', Registry::get('zone_default.minimum_ttl'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ trans('settings/model.zone_default.minimum_ttl_help') }}</span>
                            <span class="help-block">{{ $errors->first('zone_default.minimum_ttl', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ zone_default.minimum_ttl -->
                </div>
                <!-- ./ Zone's defaults settings -->

                <!-- RR's defaults settings -->
                <div class="tab-pane" id="panel_settings_tab2">
                    <!-- record_ttl_default -->
                    <div class="form-group {{ $errors->has('record_ttl_default') ? 'has-error' : '' }}">
                        {!! Form::label('record_ttl_default', trans('settings/model.record_ttl_default'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::number('record_ttl_default', Registry::get('record_ttl_default'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ trans('settings/model.record_ttl_default_help') }}</span>
                            <span class="help-block">{{ $errors->first('record_ttl_default', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ record_ttl_default -->
                </div>
                <!-- ./ RR's defaults settings -->

            </div>
        </div>
    </div>
    <div class="box-footer">
    <!-- Form Actions -->
    {!! Form::button('<i class="fa fa-floppy-o"></i> ' . trans('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
    <!-- ./ form actions -->
    </div>
</div>
{!! Form::close() !!}
