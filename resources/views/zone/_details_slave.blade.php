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
                This is an Slave zone of {{ $zone->master }} server
            </div>
        </div>
        <!-- ./ master -->

    </div>
    <div class="box-footer">
        <a href="{{ route('zones.index') }}">
            <button type="button" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
            </button>
        </a>
        <a href="{{ route('zones.edit', $zone) }}">
            <button type="button" class="btn btn-primary">
                <i class="fa fa-pencil"></i> {{ trans('general.edit') }}
            </button>
        </a>
    </div>
</div>