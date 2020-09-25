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
            {!! Form::label('master', __('zone/model.master'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ __('zone/model.types.slave') }} (Master server: {{ $zone->master_server }})
            </div>
        </div>
        <!-- ./ master -->

    </div>
    <div class="box-footer">
        <a href="{{ route('zones.index') }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
        </a>
        <a href="{{ route('zones.edit', $zone) }}" class="btn btn-primary" role="button">
                <i class="fa fa-pencil"></i> {{ __('general.edit') }}
        </a>
    </div>
</div>
