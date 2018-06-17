{{-- Create / Edit Server Form --}}
@if (isset($record))
    {!! Form::model($record, ['route' => ['zones.records.update', $zone, $record], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => ['zones.records.store', $zone]]) !!}
@endif

<div class="box box-solid">
    <div class="box-body">

        <!-- zone -->
        <div class="form-group">
            {!! Form::label('zone', trans('zone/model.domain'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $zone->domain }}
            </div>
        </div>
        <!-- ./ zone -->

        <!-- name -->
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            {!! Form::label('name', trans('record/model.name'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">
                    {{ trans('record/model.name_help') }}
                    {{ $errors->first('name', ':message') }}
                </span>
            </div>
        </div>
        <!-- ./ name -->

        <!-- ttl -->
        <div class="form-group {{ $errors->has('ttl') ? 'has-error' : '' }}">
            {!! Form::label('ttl', trans('record/model.ttl'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::number('ttl', null, array('class' => 'form-control')) !!}
                <span class="help-block">
                    {{ trans('record/model.ttl_help') }}
                    {{ $errors->first('ttl', ':message') }}
                </span>
            </div>
        </div>
        <!-- ./ ttl -->

        <!-- type -->
        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
            {!! Form::label('type', trans('record/model.type'), array('class' => 'control-label required')) !!}
            <div class="controls">
                @if(isset($record))
                    {!! Form::hidden('type', $record->type) !!}
                    {!! Form::select('type_disabled', $zone->getValidRecordTypesForThisZone(), $record->type, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                @else
                    {!! Form::select('type', $zone->getValidRecordTypesForThisZone(), null, ['class' => 'form-control', 'required' => 'required']) !!}
                @endif
                <span class="help-block">{{ $errors->first('type', ':message') }}</span>
            </div>
        </div>
        <!-- ./ type -->

        <!-- priority -->
        <div class="form-group {{ $errors->has('priority') ? 'has-error' : '' }}" id="priorityGroup">
            {!! Form::label('priority', trans('record/model.priority'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::number('priority', null, array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
                <span class="help-block">
                    {{ trans('record/model.priority_help') }}
                    {{ $errors->first('priority', ':message') }}
                </span>
            </div>
        </div>
        <!-- ./ priority -->

        <!-- data -->
        <div class="form-group {{ $errors->has('data') ? 'has-error' : '' }}">
            {!! Form::label('data', trans('record/model.data'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::text('data', null, array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ $errors->first('data', ':message') }}</span>
            </div>
        </div>
        <!-- ./ data -->

        <a class="btn btn-default" role="button" data-toggle="collapse" href="#collapseHelp" aria-expanded="false" aria-controls="collapseHelp">
            {{ trans('general.help') }}
        </a>

    </div>

    <div class="box-footer">
        <!-- Form Actions -->
        <a href="{{ route('zones.records.index', $zone) }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
        </a>
    {!! Form::button('<i class="fa fa-floppy-o"></i> ' . trans('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
    <!-- ./ form actions -->
    </div>
</div>
{!! Form::close() !!}

<div id="collapseHelp" class="collapse">
    <pre>{!! trans('record/messages.records_help') !!}</pre>
</div>

@push('scripts')
<script>
    function changeVisibility(type) {
        if (type == "MX" || type == "SRV") {
            $('#priorityGroup').show();
            $('#priority').prop('readonly', false);
        }
        else {
            $('#priorityGroup').hide();
            $('#priority').prop('readonly', true);
        }
    }
    $(function () {
        var i = $('#type').val();
        changeVisibility(i);

        $('#type').change(function () {
            var i = $('#type').val();
            changeVisibility(i);
        });
    });
</script>
@endpush
