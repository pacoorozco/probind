<div class="box">
    <div class="box-body">

        <!-- domain -->
        <div class="form-group">
            {!! Form::label('domain', __('zone/model.domain'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $zone->domain }}
            </div>
        </div>
        <!-- ./ domain -->

        <!-- name -->
        <div class="form-group">
            {!! Form::label('name', __('record/model.name'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $record->name }}
            </div>
        </div>
        <!-- ./ name -->

        <!-- type -->
        <div class="form-group">
            {!! Form::label('type', __('record/model.type'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $record->type }}
            </div>
        </div>
        <!-- ./ type -->

        <!-- data -->
        <div class="form-group">
            {!! Form::label('data', __('record/model.data'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $record->data }}
            </div>
        </div>
        <!-- ./ data -->

        <!-- ttl -->
        <div class="form-group">
            {!! Form::label('ttl', __('record/model.ttl'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $record->ttl }}
            </div>
        </div>
        <!-- ./ ttl -->

        <!-- formatted representation -->
        <div class="form-group">
            {!! Form::label('preview', __('record/model.preview'), array('class' => 'control-label')) !!}
            <div class="controls">
                <pre>
                    {{ $record->present()->asString() }}
                </pre>
            </div>
        </div>
        <!-- ./ formatted representation -->

    </div>
    <div class="box-footer">
        <a href="{{ route('zones.records.index', $zone) }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
        </a>
        @if ($action == 'show')
            <a href="{{ route('zones.records.edit', [$zone, $record]) }}" class="btn btn-primary" role="button">
                    <i class="fa fa-pencil"></i> {{ __('general.edit') }}
            </a>
        @else
            {!! Form::button('<i class="fa fa-trash-o"></i>' . __('general.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
        @endif
    </div>
</div>
