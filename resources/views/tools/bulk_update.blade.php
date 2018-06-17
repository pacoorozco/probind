@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('tools/title.bulk_update') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('tools/title.bulk_update') }}
    <small>{{ trans('tools/title.bulk_update_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{-- route('tools.index') --}}">
            {{ trans('site.tools') }}
        </a>
    </li>
    <li class="active">
        {{ trans('tools/title.bulk_update') }}
    </li>
@endsection

{{-- Content --}}
@section('content')
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Mark all zones with pending changes</h3>
        </div>
        <div class="box-body">
            <div class="callout callout-warning">
                <h4>Warning!</h4>

                <p>{{ trans('tools/messages.bulk_update_warning') }}</p>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::open(['route' => 'tools.do_bulk_update']) !!}

            <a href="{{ route('home') }}" class="btn btn-primary" role="button">
                    <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
            </a>
            {!! Form::button('<i class="fa fa-download"></i> Bulk update', array('type' => 'submit', 'class' => 'btn btn-warning pull-right')) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection
