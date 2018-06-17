<div class="box">
    <div class="box-body">

        <!-- username -->
        <div class="form-group">
            {!! Form::label('username', trans('user/model.username'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $user->username }}
                {!! (!$user->active)
                    ? ' <span class="label label-default">' . trans('general.inactive') . '</span>'
                    : '' !!}
            </div>
        </div>
        <!-- ./ username -->

        <!-- name -->
        <div class="form-group">
            {!! Form::label('name', trans('user/model.name'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $user->name }}
            </div>
        </div>
        <!-- ./ name -->

        <!-- email -->
        <div class="form-group">
            {!! Form::label('email', trans('user/model.email'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ $user->email }}
            </div>
        </div>
        <!-- ./ email -->

        <!-- active -->
        <div class="form-group">
            {!! Form::label('active', trans('user/model.active'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{ trans_choice('general.boolean', $user->active) }}
            </div>
        </div>
        <!-- ./ active -->

    </div>
    <div class="box-footer">
        <a href="{{ route('users.index') }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
        </a>
        @if ($action == 'show')
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary" role="button">
                    <i class="fa fa-pencil"></i> {{ trans('general.edit') }}
            </a>
        @else
            {!! Form::button('<i class="fa fa-trash-o"></i> ' . trans('general.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
        @endif
    </div>
</div>
