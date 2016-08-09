{{-- Create / Edit Server Form --}}
@if (isset($zone))
    {!! Form::model($zone, ['route' => ['zones.update', $zone], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'zones.store']) !!}
@endif

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ trans('zone/model.types.master') }}</h3>
    </div>
    <div class="box-body">

        <!-- domain -->
        <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
            {!! Form::label('domain', trans('zone/model.domain'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::text('domain', null, array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ $errors->first('domain', ':message') }}</span>
            </div>
        </div>
        <!-- ./ domain -->
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
