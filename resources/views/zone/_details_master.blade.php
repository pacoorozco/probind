<div class="box">
    <div class="box-body">

        <!-- domain -->
        <div class="form-group">
            {!! Form::label('name', __('zone/model.domain'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $zone->domain }}
            </div>
        </div>
        <!-- ./ domain -->

        <!-- master -->
        <div class="form-group">
            {!! Form::label('master', __('zone/model.type'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ __('zone/model.types.master') }}
            </div>
        </div>
        <!-- ./ master -->

        <!-- serial -->
        <div class="form-group">
            {!! Form::label('serial', __('zone/model.serial'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $zone->serial }}
            </div>
        </div>
        <!-- ./ serial -->

        @if($zone->custom_settings)

            <h4>{{ __('zone/title.custom_settings') }}</h4>

            <!-- refresh -->
            <div class="form-group">
                {!! Form::label('refresh', __('zone/model.refresh'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {{ $zone->refresh }}
                </div>
            </div>
            <!-- ./ refresh -->

            <!-- retry -->
            <div class="form-group">
                {!! Form::label('retry', __('zone/model.retry'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {{ $zone->retry }}
                </div>
            </div>
            <!-- ./ retry -->

            <!-- expire -->
            <div class="form-group">
                {!! Form::label('expire', __('zone/model.expire'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {{ $zone->expire }}
                </div>
            </div>
            <!-- ./ expire -->

            <!-- negative_ttl -->
            <div class="form-group">
                {!! Form::label('negative_ttl', __('zone/model.negative_ttl'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {{ $zone->negative_ttl }}
                </div>
            </div>
            <!-- ./ negative_ttl -->

            <!-- default_ttl -->
            <div class="form-group">
                {!! Form::label('default_ttl', __('zone/model.default_ttl'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {{ $zone->default_ttl }}
                </div>
            </div>
            <!-- ./ default_ttl -->
        @endif

    </div>
    <div class="box-footer">
        <a href="{{ route('zones.index') }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
        </a>
        <a href="{{ route('zones.edit', $zone) }}" class="btn btn-primary" role="button">
                <i class="fa fa-pencil"></i> {{ __('general.edit') }}
        </a>
        <div class="pull-right">
            <a href="{{ route('zones.records.index', $zone) }}" class="btn btn-info" role="button">
                    <i class="fa fa-database"></i> {{ __('record/title.view_records') }}
            </a>
            <a href="{{ route('zones.records.create', $zone) }}" class="btn btn-success" role="button">
                    <i class="fa fa-plus"></i> {{ __('record/title.create_new') }}
            </a>
        </div>
    </div>
</div>
