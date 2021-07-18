<div class="box">
    <div class="box-body">

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
                {{ __('zone/model.types.slave') }}
            </div>
        </div>
        <!-- ./ type -->

        <!-- master -->
        <div class="form-group">
            {!! Form::label('master', __('zone/model.server'), ['class' => 'control-label']) !!}
            <div class="controls">
                {{ $zone->present()->server }}
            </div>
        </div>
        <!-- ./ master -->

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
    <div class="box-footer">
        <a href="{{ route('zones.index') }}" class="btn btn-default" role="button">
                <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
        </a>
        <a href="{{ route('zones.edit', $zone) }}" class="btn btn-primary pull-right" role="button">
                <i class="fa fa-pencil"></i> {{ __('general.edit') }}
        </a>
    </div>
</div>
