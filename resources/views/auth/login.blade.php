@extends('layouts.login')

{{-- Web site Title --}}
@section('title')
    {{ trans('auth.login') }}
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('site.dashboard') }}
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="active">
        <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
    </li>
@endsection

{{-- Content --}}
@section('content')
    <!-- start: LOGIN BOX -->
    <p class="login-box-msg">{{ trans('auth.sign_title') }}</p>

    {!! Form::open(array('url' => '/login')) !!}
    <div class="form-group has-feedback">
        {!! Form::text('username', null, array(
                    'class' => 'form-control',
                    'placeholder' => trans('auth.username'),
                    'required' => 'required',
                    'autofocus' => 'autofocus'
                    )) !!}
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        {!! Form::password('password', array(
                    'class' => 'form-control password',
                    'placeholder' => trans('auth.password'),
                    'required' => 'required'
                    )) !!}
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <div class="checkbox icheck">
                <label>
                    {!! Form::checkbox('remember', '1', false) !!}
                    {{ trans('auth.remember_me') }}
                </label>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
            {!! Form::button(trans('auth.login'), ['type' => 'submit', 'class' => 'btn btn-primary btn-block btn-flat', 'id' => 'submit']) !!}
        </div>
        <!-- /.col -->
    </div>
    {!! Form::close() !!}
    {{--
    <a href="{{ url('/password/reset') }}">
        {{ trans('auth.forgot_password') }}
    </a><br>
    <a href="{{ url('auth/register') }}" class="text-center">
        {{ trans('auth.create_account') }}
    </a>
    --}}
    <!-- end: LOGIN BOX -->
@endsection

@push('scripts')
    <script>
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });
    </script>
@endpush
