@extends('layouts.installer')

{{-- Web site Title --}}
@section('title')
    {{ trans('installer.welcome.title') }}
@endsection

@section('content')
    <div class="container">
        <div class="register-logo">
            <a href="{{ route('home') }}"><b>ProBIND</b> v3</a>
        </div>

        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <i class="fa fa-database"></i>
                <h3 class="box-title">{{ trans('installer.welcome.header') }}</h3>
            </div>
            <div class="box-body">
                <p>{{ trans('installer.welcome.sub-title') }}</p>
            </div>
            <div class="box-footer">
                <a href="{{ route('zones.records.create', $zone) }}">
                    <button type="button" class="btn btn-success margin-bottom">
                        <i class="fa fa-plus"></i> {{ trans('record/title.create_new') }}
                    </button>
                </a>

            </div>
        </div>
    </div>
@endsection