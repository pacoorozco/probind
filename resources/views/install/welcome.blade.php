@extends('layouts.installer')

{{-- Web site Title --}}
@section('title')
    {{ __('installer.welcome.title') }}
@endsection

@section('content')
    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <i class="fa fa-database"></i>
            <h3 class="box-title">{{ __('installer.welcome.header') }}</h3>
        </div>
        <div class="box-body">
            <p>{{ __('installer.welcome.sub-title') }}</p>
        </div>
        <div class="box-footer">
            <a href="{{ route('Installer::database') }}" class="btn btn-primary margin-bottom pull-right" role="button">
                    {{ __('general.next') }} <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endsection
