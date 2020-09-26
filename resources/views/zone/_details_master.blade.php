<div class="box">
    <div class="box-body">

        <div class="row">
            <div class="col-md-6">
                <!-- domain -->
                <div class="form-group">
                    {!! Form::label('name', __('zone/model.domain'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ $zone->present()->domain }}
                    </div>
                </div>
                <!-- ./ domain -->

                <!-- type -->
                <div class="form-group">
                    {!! Form::label('master', __('zone/model.type'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ __('zone/model.types.master') }}
                    </div>
                </div>
                <!-- ./ type -->

                <!-- serial -->
                <div class="form-group">
                    {!! Form::label('serial', __('zone/model.serial'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ $zone->present()->serial }}
                    </div>
                </div>
                <!-- ./ serial -->

                <!-- created_at -->
                <div class="form-group">
                    {!! Form::label('serial', __('zone/model.created_at'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ $zone->present()->created_at }}
                    </div>
                </div>
                <!-- ./ created_at -->

                <!-- updated_at -->
                <div class="form-group">
                    {!! Form::label('serial', __('zone/model.updated_at'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ $zone->present()->updated_at }}
                    </div>
                </div>
                <!-- ./ updated_at -->

                <!-- status -->
                <div class="form-group">
                    {!! Form::label('serial', __('zone/model.status'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ $zone->present()->statusBadge }}
                    </div>
                </div>
                <!-- ./ status -->

            </div>
            <div class="col-md-6">

                {{ $zone->present()->custom_settings }}

                <!-- refresh -->
                <div class="form-group">
                    {!! Form::label('refresh', __('zone/model.refresh'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ $zone->present()->refresh }}
                    </div>
                </div>
                <!-- ./ refresh -->

                <!-- retry -->
                <div class="form-group">
                    {!! Form::label('retry', __('zone/model.retry'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ $zone->present()->retry }}
                    </div>
                </div>
                <!-- ./ retry -->

                <!-- expire -->
                <div class="form-group">
                    {!! Form::label('expire', __('zone/model.expire'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ $zone->present()->expire }}
                    </div>
                </div>
                <!-- ./ expire -->

                <!-- negative_ttl -->
                <div class="form-group">
                    {!! Form::label('negative_ttl', __('zone/model.negative_ttl'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ $zone->present()->negativeTtl }}
                    </div>
                </div>
                <!-- ./ negative_ttl -->

                <!-- default_ttl -->
                <div class="form-group">
                    {!! Form::label('default_ttl', __('zone/model.default_ttl'), ['class' => 'control-label']) !!}
                    <div class="controls">
                        {{ $zone->present()->defaultTtl }}
                    </div>
                </div>
                <!-- ./ default_ttl -->
            </div>
        </div>

    </div>
    <div class="box-footer">
        <a href="{{ route('zones.index') }}" class="btn btn-default" role="button">
            <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
        </a>
        <a href="{{ route('zones.edit', $zone) }}" class="btn btn-primary pull-right" role="button">
            <i class="fa fa-pencil"></i> {{ __('general.edit') }}
        </a>
    </div>
</div>
