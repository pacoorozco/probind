@extends('layouts.installer')

{{-- Web site Title --}}
@section('title')
    {{ trans('installer.welcome.title') }}
@endsection

@section('content')
    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <i class="fa fa-database"></i>
            <h3 class="box-title">{{ trans('installer.welcome.header') }}</h3>
        </div>
        <div class="box-body">
            <p>{{ trans('installer.welcome.sub-title') }}</p>
        </div>
        <div class="box-footer">
            <a href="{{ route('Installer::database') }}">
                <button type="button" class="btn btn-primary margin-bottom pull-right">
                    {{ trans('general.next') }} <i class="fa fa-arrow-right"></i>
                </button>
            </a>

        </div>
    </div>
@endsection
