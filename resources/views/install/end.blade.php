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
            <a href="{{ route('login') }}">
                <button type="button" class="btn btn-primary margin-bottom pull-center">
                    {{ trans('auth.login') }}
                </button>
            </a>

        </div>
    </div>
@endsection
