<div class="box">
    <div class="box-body">

        <!-- hostname -->
        <div class="form-group">
            {!! Form::label('name', trans('server/model.hostname'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->hostname }}
            </div>
        </div>
        <!-- ./ hostname -->

        <!-- ip_address -->
        <div class="form-group">
            {!! Form::label('ip_address', trans('server/model.ip_address'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->ip_address }}
            </div>
        </div>
        <!-- ./ ip_address -->

        <!-- type -->
        <div class="form-group">
            {!! Form::label('type', trans('server/model.type'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->type }}
            </div>
        </div>
        <!-- ./ type -->

        <!-- push_updates -->
        <div class="form-group">
            {!! Form::label('push_updates', trans('server/model.push_updates'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->push_updates }}
            </div>
        </div>
        <!-- ./ push_updates -->

        <!-- ns_record -->
        <div class="form-group">
            {!! Form::label('ns_record', trans('server/model.ns_record'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->ns_record }}
            </div>
        </div>
        <!-- ./ ns_record -->

        <!-- directory -->
        <div class="form-group">
            {!! Form::label('directory', trans('server/model.directory'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->directory }}
            </div>
        </div>
        <!-- ./ directory -->

        <!-- template -->
        <div class="form-group">
            {!! Form::label('template', trans('server/model.template'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->template }}
            </div>
        </div>
        <!-- ./ template -->

        <!-- script -->
        <div class="form-group">
            {!! Form::label('script', trans('server/model.script'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->script }}
            </div>
        </div>
        <!-- ./ script -->

        <!-- active -->
        <div class="form-group">
            {!! Form::label('active', trans('server/model.active'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->active }}
            </div>
        </div>
        <!-- ./ active -->

    </div>
    <div class="box-footer">
        <a href="{{ route('servers.index') }}">
            <button type="button" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
            </button>
        </a>
        @if ($action == 'show')
            <a href="{{ route('servers.edit', $server) }}">
                <button type="button" class="btn btn-primary">
                    <i class="fa fa-pencil"></i> {{ trans('general.edit') }}
                </button>
            </a>
        @else
            {!! Form::button('<i class="fa fa-trash-o"></i>' . trans('general.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
        @endif
    </div>
</div>

