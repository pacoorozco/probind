{{-- Create / Edit Server Form --}}
@if (isset($zone))
    {!! Form::model($zone, ['route' => ['zones.update', $zone], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'zones.store']) !!}
@endif

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ __('zone/title.primary_dns_zone') }}</h3>
    </div>
    <div class="box-body">

        <!-- zone_type -->
        {!! Form::hidden('zone_type', \App\Enums\ZoneType::Primary) !!}
        <!-- ./zone_type -->

        <!-- domain -->
        <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
            {!! Form::label('domain', __('zone/model.domain'), ['class' => 'control-label required']) !!}
            <div class="controls">
                @if (isset($zone))
                    {!! Form::text('domain', null, ['class' => 'form-control', 'disabled' => 'disabled', 'id' => 'primary_domain']) !!}
                @else
                    {!! Form::text('domain', null, ['class' => 'form-control', 'required' => 'required', 'id' => 'primary_domain']) !!}
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
                    {{ __('zone/model.custom_settings') }}
                </label>
            </div>
        </div>
        <!-- ./ custom_settings -->

        <!-- custom settings section -->
        <div class="{{ (empty($zone->custom_settings) && empty(old('custom_settings'))) ? 'collapse' : 'collapse in' }}"
             id="custom_settings_section">

            <h4>{{ __('zone/title.custom_settings') }}</h4>

            <fieldset id="custom_settings_fields">

                <!-- Copy from global settings -->
                <div class="form-group">
                    <button class="btn btn-default" type="button" id="copy_global_settings">
                        {{ __('zone/model.copy_values_from_defaults') }}
                    </button>
                </div>
                <!-- ./ Copy from global settings -->

                <!-- row -->
                <div class="row">
                    <div class="col-md-6">
                        <!-- refresh -->
                        <div class="form-group {{ $errors->has('refresh') ? 'has-error' : '' }}">
                            {!! Form::label('refresh', __('zone/model.refresh'), ['class' => 'control-label required']) !!}
                            <div class="controls">
                                {!! Form::number('refresh', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                <span class="help-block">{{ __('zone/model.refresh_help') }}</span>
                                <span class="help-block">{{ $errors->first('refresh', ':message') }}</span>
                            </div>
                        </div>
                        <!-- ./ refresh -->
                    </div>
                    <div class="col-md-6">

                        <!-- retry -->
                        <div class="form-group {{ $errors->has('retry') ? 'has-error' : '' }}">
                            {!! Form::label('retry', __('zone/model.retry'), ['class' => 'control-label required']) !!}
                            <div class="controls">
                                {!! Form::number('retry', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                <span class="help-block">{{ __('zone/model.retry_help') }}</span>
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
                            {!! Form::label('expire', __('zone/model.expire'), ['class' => 'control-label required']) !!}
                            <div class="controls">
                                {!! Form::number('expire', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                <span class="help-block">{{ __('zone/model.expire_help') }}</span>
                                <span class="help-block">{{ $errors->first('expire', ':message') }}</span>
                            </div>
                        </div>
                        <!-- ./ expire -->
                    </div>
                    <div class="col-md-6">

                        <!-- negative_ttl -->
                        <div class="form-group {{ $errors->has('negative_ttl') ? 'has-error' : '' }}">
                            {!! Form::label('negative_ttl', __('zone/model.negative_ttl'), ['class' => 'control-label required']) !!}
                            <div class="controls">
                                {!! Form::number('negative_ttl', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                <span class="help-block">{{ __('zone/model.negative_ttl_help') }}</span>
                                <span class="help-block">{{ $errors->first('negative_ttl', ':message') }}</span>
                            </div>
                        </div>
                        <!-- ./ negative_ttl -->
                    </div>
                </div>
                <!-- ./ row -->

                <!-- default_ttl -->
                <div class="form-group {{ $errors->has('default_ttl') ? 'has-error' : '' }}">
                    {!! Form::label('default_ttl', __('zone/model.default_ttl'), ['class' => 'control-label required']) !!}
                    <div class="controls">
                        {!! Form::number('default_ttl', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        <span class="help-block">{{ __('zone/model.default_ttl_help') }}</span>
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
        <a href="{{ route('zones.index') }}" class="btn btn-default" role="button">
                <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
        </a>
    {!! Form::button('<i class="fa fa-floppy-o"></i> ' . __('general.save'), ['type' => 'submit', 'class' => 'btn btn-success pull-right', 'id' => 'master_zone']) !!}
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
