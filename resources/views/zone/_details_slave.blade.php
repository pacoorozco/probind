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
            {!! Form::label('master', trans('zone/model.master'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ trans('zone/model.types.slave') }} (Master server: {{ $zone->master_server }})
            </div>
        </div>
        <!-- ./ master -->

    </div>
    <div class="box-footer">
        <a href="{{ route('zones.index') }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
        </a>
        <a href="{{ route('zones.edit', $zone) }}" class="btn btn-primary" role="button">
                <i class="fa fa-pencil"></i> {{ trans('general.edit') }}
        </a>
    </div>
</div>
