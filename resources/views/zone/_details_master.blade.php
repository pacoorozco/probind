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
        <div class="pull-right">
            <a href="{{ route('zones.records.index', $zone) }}">
                <button type="button" class="btn btn-info">
                    <i class="fa fa-database"></i> {{ trans('record/title.view_records') }}
                </button>
            </a>
            <a href="{{ route('zones.records.create', $zone) }}">
                <button type="button" class="btn btn-success">
                    <i class="fa fa-plus"></i> {{ trans('record/title.create_new') }}
                </button>
            </a>
        </div>
    </div>
</div>