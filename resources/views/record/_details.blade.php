<div class="box">
    <div class="box-body">

        <!-- domain -->
        <div class="form-group">
            {!! Form::label('domain', trans('zone/model.domain'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $zone->domain }}
            </div>
        </div>
        <!-- ./ domain -->

        <!-- name -->
        <div class="form-group">
            {!! Form::label('name', trans('record/model.name'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $record->name }}
            </div>
        </div>
        <!-- ./ name -->

        <!-- ttl -->
        <div class="form-group">
            {!! Form::label('ttl', trans('record/model.ttl'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $record->ttl }}
            </div>
        </div>
        <!-- ./ ttl -->

        <!-- type -->
        <div class="form-group">
            {!! Form::label('type', trans('record/model.type'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $record->type }}
            </div>
        </div>
        <!-- ./ type -->

        @if($record->type == 'MX' || $record->type =='SRV')
        <!-- priority -->
        <div class="form-group">
            {!! Form::label('priority', trans('record/model.priority'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $record->priority }}
            </div>
        </div>
        <!-- ./ priority -->
        @endif

        <!-- data -->
        <div class="form-group">
            {!! Form::label('data', trans('record/model.data'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $record->data }}
            </div>
        </div>
        <!-- ./ data -->

    </div>
    <div class="box-footer">
        <a href="{{ route('zones.records.index', $zone) }}">
            <button type="button" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
            </button>
        </a>
        @if ($action == 'show')
            <a href="{{ route('zones.records.edit', [$zone, $record]) }}">
                <button type="button" class="btn btn-primary">
                    <i class="fa fa-pencil"></i> {{ trans('general.edit') }}
                </button>
            </a>
        @else
            {!! Form::button('<i class="fa fa-trash-o"></i>' . trans('general.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
        @endif
    </div>
</div>