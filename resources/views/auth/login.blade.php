@extends('layouts.login')

{{-- Web site Title --}}
@section('title')
    {{ __('auth.login') }}
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('site.dashboard') }}
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="active">
        <i class="fa fa-dashboard"></i> {{ __('site.dashboard') }}
    </li>
@endsection

{{-- Content --}}
@section('content')
    <!-- start: LOGIN BOX -->
    <p class="login-box-msg">{{ __('auth.sign_title') }}</p>

    {!! Form::open(array('url' => '/login')) !!}
    <div class="form-group has-feedback">
        {!! Form::text('username', null, array(
                    'class' => 'form-control',
                    'placeholder' => __('auth.username'),
                    'required' => 'required',
                    'autofocus' => 'autofocus'
                    )) !!}
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        {!! Form::password('password', array(
                    'class' => 'form-control password',
                    'placeholder' => __('auth.password'),
                    'required' => 'required'
                    )) !!}
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <div class="checkbox icheck">
                <label>
                    {!! Form::checkbox('remember', '1', false) !!}
                    {{ __('auth.remember_me') }}
                </label>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
            {!! Form::button(__('auth.login'), ['type' => 'submit', 'class' => 'btn btn-primary btn-block btn-flat', 'id' => 'submit']) !!}
        </div>
        <!-- /.col -->
    </div>
    {!! Form::close() !!}
    {{--
    <a href="{{ url('/password/reset') }}">
        {{ __('auth.forgot_password') }}
    </a><br>
    <a href="{{ url('auth/register') }}" class="text-center">
        {{ __('auth.create_account') }}
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
