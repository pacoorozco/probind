{{-- Create / Edit user Form --}}
@if (isset($user))
    {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'users.store']) !!}
@endif

<div class="box box-solid">
    <div class="box-body">

        <!-- username -->
        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            {!! Form::label('username', trans('user/model.username'), array('class' => 'control-label required')) !!}
            <div class="controls">
                @if (isset($user))
                    {!! Form::text('username', null, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                @else
                    {!! Form::text('username', null, array('class' => 'form-control', 'required' => 'required')) !!}
                @endif
                <span class="help-block">{{ $errors->first('username', ':message') }}</span>
            </div>
        </div>
        <!-- ./ username -->

        <!-- name -->
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            {!! Form::label('name', trans('user/model.name'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>
        <!-- ./ name -->

        <!-- email -->
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            {!! Form::label('email', trans('user/model.email'), array('class' => 'control-label required')) !!}
            <div class="controls">
                {!! Form::email('email', null, array('class' => 'form-control', 'required' => 'required')) !!}
                {{ $errors->first('email', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ email -->

        <!-- password -->
        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            {!! Form::label('password', trans('user/model.password'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::password('password', array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
            </div>
        </div>
        <!-- ./ password -->

        <!-- password_confirmation -->
        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            {!! Form::label('password_confirmation', trans('user/model.password_confirmation'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
            </div>
        </div>
        <!-- ./ password_confirmation -->

        @if (isset($user))

        <!-- active -->
            <div class="form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                {!! Form::label('active', trans('user/model.active'), array('class' => 'control-label required')) !!}
                <div class="controls">
                    {!! Form::select('active', array('1' => trans('general.yes'), '0' => trans('general.no')), null, array('class' => 'form-control', 'required' => 'required')) !!}
                    {{ $errors->first('active', '<span class="help-inline">:message</span>') }}
                </div>
            </div>
            <!-- ./ active -->

        @endif
    </div>

    <div class="box-footer">
        <!-- Form Actions -->
        <a href="{{ route('users.index') }}" class="btn btn-primary" role="button">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
        </a>
    {!! Form::button('<i class="fa fa-floppy-o"></i> ' . trans('general.save'), array('type' => 'submit', 'class' => 'btn btn-success', 'id' => 'submit')) !!}
    <!-- ./ form actions -->
    </div>
</div>
{!! Form::close() !!}
