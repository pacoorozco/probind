{{-- Create / Edit Server Form --}}
@if (isset($zone))
    {!! Form::model($zone, ['route' => ['zones.update', $zone], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'zones.store']) !!}
@endif

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ trans('zone/model.types.master') }}</h3>
    </div>
    <div class="box-body">

        <!-- domain -->
        <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
            {!! Form::label('domain', trans('zone/model.domain'), array('class' => 'control-label required')) !!}
            <div class="controls">
                @if (isset($zone))
                    {!! Form::text('domain', null, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                @else
                    {!! Form::text('domain', null, array('class' => 'form-control', 'required' => 'required')) !!}
                @endif
                <span class="help-block">{{ $errors->first('domain', ':message') }}</span>
            </div>
        </div>
        <!-- ./ domain -->

        <!-- custom_settings -->
        <div class="form-group {{ $errors->has('custom_settings') ? 'has-error' : '' }}">
            <div class="checkbox">
                <label class="control-label" data-toggle="collapse" data-target="#custom_settings_section">
                    {{ Form::checkbox('custom_settings', true, null, ['id' => 'custom_settings']) }}
                    {{ trans('zone/model.custom_settings') }}
                </label>
            </div>
        </div>
        <!-- ./ custom_settings -->

        <!-- custom settings section -->
        <div class="{{ (empty($zone->custom_settings) && empty(old('custom_settings'))) ? 'collapse' : 'collapse in' }}"
             id="custom_settings_section">

            <h4>{{ trans('zone/title.custom_settings') }}</h4>

            <fieldset id="custom_settings_fields">

                <!-- Copy from global settings -->
                <div class="form-group">
                    <button class="btn btn-default" type="button" id="copy_global_settings">Copy values from defaults
                    </button>
                </div>
                <!-- ./ Copy from global settings -->

                <!-- row -->
                <div class="row">
                    <div class="col-md-6">
                        <!-- refresh -->
                        <div class="form-group {{ $errors->has('refresh') ? 'has-error' : '' }}">
                            {!! Form::label('refresh', trans('zone/model.refresh'), array('class' => 'control-label required')) !!}
                            <div class="controls">
                                {!! Form::number('refresh', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                <span class="help-block">{{ trans('zone/model.refresh_help') }}</span>
                                <span class="help-block">{{ $errors->first('refresh', ':message') }}</span>
                            </div>
                        </div>
                        <!-- ./ refresh -->
                    </div>
                    <div class="col-md-6">

                        <!-- retry -->
                        <div class="form-group {{ $errors->has('retry') ? 'has-error' : '' }}">
                            {!! Form::label('retry', trans('zone/model.retry'), array('class' => 'control-label required')) !!}
                            <div class="controls">
                                {!! Form::number('retry', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                <span class="help-block">{{ trans('zone/model.retry_help') }}</span>
                                <span class="help-block">{{ $errors->first('retry', ':message') }}</span>
                            </div>
                        </div>
                        <!-- ./ retry -->
                    </div>
                </div>
                <!-- ./ row -->

                <!-- row -->
                <div class="row">
                    <div class="col-md-6">
                        <!-- expire -->
                        <div class="form-group {{ $errors->has('expire') ? 'has-error' : '' }}">
                            {!! Form::label('expire', trans('zone/model.expire'), array('class' => 'control-label required')) !!}
                            <div class="controls">
                                {!! Form::number('expire', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                <span class="help-block">{{ trans('zone/model.expire_help') }}</span>
                                <span class="help-block">{{ $errors->first('expire', ':message') }}</span>
                            </div>
                        </div>
                        <!-- ./ expire -->
                    </div>
                    <div class="col-md-6">

                        <!-- negative_ttl -->
                        <div class="form-group {{ $errors->has('negative_ttl') ? 'has-error' : '' }}">
                            {!! Form::label('negative_ttl', trans('zone/model.negative_ttl'), array('class' => 'control-label required')) !!}
                            <div class="controls">
                                {!! Form::number('negative_ttl', null, array('class' => 'form-control', 'required' => 'required')) !!}
                                <span class="help-block">{{ trans('zone/model.negative_ttl_help') }}</span>
                                <span class="help-block">{{ $errors->first('negative_ttl', ':message') }}</span>
                            </div>
                        </div>
                        <!-- ./ negative_ttl -->
                    </div>
                </div>
                <!-- ./ row -->

                <!-- default_ttl -->
                <div class="form-group {{ $errors->has('default_ttl') ? 'has-error' : '' }}">
                    {!! Form::label('default_ttl', trans('zone/model.default_ttl'), array('class' => 'control-label required')) !!}
                    <div class="controls">
                        {!! Form::number('default_ttl', null, array('class' => 'form-control', 'required' => 'required')) !!}
                        <span class="help-block">{{ trans('zone/model.default_ttl_help') }}</span>
                        <span class="help-block">{{ $errors->first('default_ttl', ':message') }}</span>
                    </div>
                </div>
                <!-- ./ default_ttl -->

            </fieldset>
        </div>
        <!-- ./ custom settings section -->
    </div>

    <div class="box-footer">
        <!-- Form Actions -->
        <a href="{{ route('zones.index') }}">
            <button type="button" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
            </button>
        </a>
    {!! Form::button('<i class="fa fa-floppy-o"></i> ' . trans('general.save'), array('type' => 'submit', 'class' => 'btn btn-success', 'id' => 'master_zone')) !!}
    <!-- ./ form actions -->
    </div>
</div>
{!! Form::close() !!}

@push('scripts')
<script>
    $(function () {
        $("#custom_settings_fields").prop("disabled", !$("#custom_settings").prop("checked"));

        $("#custom_settings").change(function () {
            $("#custom_settings_fields").prop("disabled", !this.checked);
        });

        $("#copy_global_settings").click(function () {
            $("#refresh").val("{{ setting('zone_default_refresh') }}");
            $("#retry").val("{{ setting('zone_default_retry') }}");
            $("#expire").val("{{ setting('zone_default_expire') }}");
            $("#negative_ttl").val("{{ setting('zone_default_negative_ttl') }}");
            $("#default_ttl").val("{{ setting('zone_default_default_ttl') }}");
        });
    });
</script>
@endpush
