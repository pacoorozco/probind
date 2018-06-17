@extends('layouts.installer')

{{-- Web site Title --}}
@section('title')
    {{ trans('installer.end.title') }}
@endsection

@section('content')
    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <i class="fa fa-database"></i>
            <h3 class="box-title">{{ trans('installer.end.header') }}</h3>
        </div>
        <div class="box-body">
            <p>{{ trans('installer.end.sub-title') }}</p>
        </div>
        <div class="box-footer">
            <a href="{{ route('login') }}" class="btn btn-primary margin-bottom pull-center" role="button">
                    {{ trans('auth.login') }}
            </a>

        </div>
    </div>
@endsection
