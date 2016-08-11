{{-- Create / Edit Server Form --}}
@if (isset($server))
    {!! Form::model($server, ['route' => ['servers.update', $server], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'servers.store']) !!}
@endif

<div class="box box-solid">
    <div class="box-body">

        <!-- hostname -->
        <div class="form-group {{ $errors->has('hostname') ? 'has-error' : '' }}">
            {!! Form::label('hostname', trans('server/model.hostname'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::text('hostname', null, array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ $errors->first('hostname', ':message') }}</span>
            </div>
        </div>
        <!-- ./ hostname -->

        <!-- ip_address -->
        <div class="form-group {{ $errors->has('ip_address') ? 'has-error' : '' }}">
            {!! Form::label('ip_address', trans('server/model.ip_address'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::text('ip_address', null, array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ $errors->first('ip_address', ':message') }}</span>
            </div>
        </div>
        <!-- ./ ip_address -->

        <!-- type -->
        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
            {!! Form::label('type', trans('server/model.type'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::select('type', array('master' => trans('server/model.types.master'), 'slave' => trans('server/model.types.slave')), null, array('class' => 'form-control', 'required' => 'required')) !!}
                {{ $errors->first('type', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ type -->

        <!-- ns_record -->
        <div class="form-group {{ $errors->has('ns_record') ? 'has-error' : '' }}">
            <div class="checkbox">
                <label class="control-label">
                    {{ Form::checkbox('ns_record', true, null) }}
                    {{ trans('server/model.ns_record') }}
                </label>
            </div>
        </div>
        <!-- ./ ns_record -->

        <!-- active -->
        <div class="form-group {{ $errors->has('active') ? 'has-error' : '' }}">
            {!! Form::label('active', trans('server/model.active'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::select('active', array('1' => trans('general.yes'), '0' => trans('general.no')), null, array('class' => 'form-control', 'required' => 'required')) !!}
                {{ $errors->first('active', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ active -->

        <h4>{{ trans('server/title.push_updates_configuration') }}</h4>

        <!-- push_updates -->
        <div class="form-group {{ $errors->has('push_updates') ? 'has-error' : '' }}">
            <div class="checkbox">
                <label class="control-label">
                    {{ Form::checkbox('push_updates', true, null) }}
                    {{ trans('server/model.push_updates') }}
                </label>
            </div>
        </div>
        <!-- ./ push_updates -->

        <!-- directory -->
        <div class="form-group {{ $errors->has('directory') ? 'has-error' : '' }}">
            {!! Form::label('directory', trans('server/model.directory'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('directory', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('directory', ':message') }}</span>
            </div>
        </div>
        <!-- ./ directory -->

        <!-- template -->
        <div class="form-group {{ $errors->has('template') ? 'has-error' : '' }}">
            {!! Form::label('template', trans('server/model.template'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('template', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('template', ':message') }}</span>
            </div>
        </div>
        <!-- ./ template -->

        <!-- script -->
        <div class="form-group {{ $errors->has('script') ? 'has-error' : '' }}">
            {!! Form::label('script', trans('server/model.script'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('script', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('script', ':message') }}</span>
            </div>
        </div>
        <!-- ./ script -->

    </div>

    <div class="box-footer">
        <!-- Form Actions -->
        <a href="{{ route('servers.index') }}">
            <button type="button" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
            </button>
        </a>
    {!! Form::button('<i class="fa fa-floppy-o"></i> ' . trans('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
    <!-- ./ form actions -->
    </div>
</div>
{!! Form::close() !!}

@push('scripts')
<script>
    $('#push_updates').change(function(){


        if ($('#push_updates').is(':checked') == true){
            $( "#directory" ).prop( "disabled", false );
            $( "#template" ).prop( "disabled", false );
            $( "#script" ).prop( "disabled", false );
            console.log('checked');
        } else {
            $( "#directory" ).prop( "disabled", true );
            $( "#template" ).prop( "disabled", true );
            $( "#script" ).prop( "disabled", true );
            console.log('unchecked');
        }

    });
</script>
@endpush
