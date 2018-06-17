<div class="box">
    <div class="box-body">

        <!-- domain -->
        <div class="form-group">
            {!! Form::label('name', trans('zone/model.domain'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $zone->domain }}
            </div>
        </div>
        <!-- ./ domain -->

        <!-- master -->
        <div class="form-group">
            {!! Form::label('master', trans('zone/model.type'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ trans('zone/model.types.master') }}
            </div>
        </div>
        <!-- ./ master -->

        <!-- serial -->
        <div class="form-group">
            {!! Form::label('serial', trans('zone/model.serial'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $zone->serial }}
            </div>
        </div>
        <!-- ./ serial -->

        @if($zone->custom_settings)

            <h4>{{ trans('zone/title.custom_settings') }}</h4>

            <!-- refresh -->
            <div class="form-group">
                {!! Form::label('refresh', trans('zone/model.refresh'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {{ $zone->refresh }}
                </div>
            </div>
            <!-- ./ refresh -->

            <!-- retry -->
            <div class="form-group">
                {!! Form::label('retry', trans('zone/model.retry'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {{ $zone->retry }}
                </div>
            </div>
            <!-- ./ retry -->

            <!-- expire -->
            <div class="form-group">
                {!! Form::label('expire', trans('zone/model.expire'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {{ $zone->expire }}
                </div>
            </div>
            <!-- ./ expire -->

            <!-- negative_ttl -->
            <div class="form-group">
                {!! Form::label('negative_ttl', trans('zone/model.negative_ttl'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {{ $zone->negative_ttl }}
                </div>
            </div>
            <!-- ./ negative_ttl -->

            <!-- default_ttl -->
            <div class="form-group">
                {!! Form::label('default_ttl', trans('zone/model.default_ttl'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {{ $zone->default_ttl }}
                </div>
            </div>
            <!-- ./ default_ttl -->
        @endif

    </div>
    <div class="box-footer">
        <a href="{{ route('zones.index') }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
        </a>
        <a href="{{ route('zones.edit', $zone) }}" class="btn btn-primary" role="button">
                <i class="fa fa-pencil"></i> {{ trans('general.edit') }}
        </a>
        <div class="pull-right">
            <a href="{{ route('zones.records.index', $zone) }}" class="btn btn-info" role="button">
                    <i class="fa fa-database"></i> {{ trans('record/title.view_records') }}
            </a>
            <a href="{{ route('zones.records.create', $zone) }}" class="btn btn-success" role="button">
                    <i class="fa fa-plus"></i> {{ trans('record/title.create_new') }}
            </a>
        </div>
    </div>
</div>
