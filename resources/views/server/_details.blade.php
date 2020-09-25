<div class="box">
    <div class="box-body">

        <!-- hostname -->
        <div class="form-group">
            {!! Form::label('hostname', __('server/model.hostname'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->hostname }}
                {!! (!$server->active)
                    ? ' <span class="label label-default">' . __('general.inactive') . '</span>'
                    : '' !!}
            </div>
        </div>
        <!-- ./ hostname -->

        <!-- ip_address -->
        <div class="form-group">
            {!! Form::label('ip_address', __('server/model.ip_address'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $server->ip_address }}
            </div>
        </div>
        <!-- ./ ip_address -->

        <!-- type -->
        <div class="form-group">
            {!! Form::label('type', __('server/model.type'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ __('server/model.types.' . $server->type) }}
            </div>
        </div>
        <!-- ./ type -->

        <!-- ns_record -->
        <div class="form-group">
            {!! Form::label('ns_record', __('server/model.ns_record'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ ___choice('general.boolean', $server->ns_record) }}
            </div>
        </div>
        <!-- ./ ns_record -->

        <!-- push_updates -->
        <div class="form-group">
            {!! Form::label('push_updates', __('server/model.push_updates'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ ___choice('general.boolean', $server->push_updates) }}
            </div>
        </div>
        <!-- ./ push_updates -->

    </div>
    <div class="box-footer">
        <a href="{{ route('servers.index') }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
        </a>
        @if ($action == 'show')
            <a href="{{ route('servers.edit', $server) }}" class="btn btn-primary" role="button">
                    <i class="fa fa-pencil"></i> {{ __('general.edit') }}
            </a>
        @else
            {!! Form::button('<i class="fa fa-trash-o"></i>' . __('general.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
        @endif
    </div>
</div>
