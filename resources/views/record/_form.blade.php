{{-- Create / Edit Server Form --}}
@if (isset($record))
    {!! Form::model($record, ['route' => ['zones.records.update', $record->zone], 'method' => 'put']) !!}
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
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>
        <!-- ./ name -->

        <!-- type -->
        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
            {!! Form::label('type', trans('record/model.type'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::text('type', null, array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ $errors->first('type', ':message') }}</span>
            </div>
        </div>
        <!-- ./ type -->

        <!-- data -->
        <div class="form-group {{ $errors->has('data') ? 'has-error' : '' }}">
            {!! Form::label('data', trans('record/model.data'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::text('data', null, array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ $errors->first('data', ':message') }}</span>
            </div>
        </div>
        <!-- ./ data -->
    </div>

    <div class="box-footer">
        <!-- Form Actions -->
        <a href="{{ route('zones.index') }}">
            <button type="button" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
            </button>
        </a>
    {!! Form::button('<i class="fa fa-floppy-o"></i> ' . trans('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
    <!-- ./ form actions -->
    </div>
</div>
{!! Form::close() !!}
