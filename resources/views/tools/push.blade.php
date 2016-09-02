@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('tools/title.push_updates') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('tools/title.push_updates') }}
    <small>{{ trans('tools/title.push_updates_subtitle') }}</small>
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
        {{ trans('tools/title.push_updates') }}
    </li>
@endsection

{{-- Content --}}
@section('content')
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('tools/title.pushing_updates') }}</h3>
        </div>

            @if($servers->isEmpty())
                @include('tools._nothing_to_do', ['info_message' => trans('tools/messages.push_updates_no_servers')])
            @elseif($zonesToUpdate->isEmpty() && $zonesToDelete->isEmpty())
                @include('tools._nothing_to_do', ['info_message' => trans('tools/messages.push_updates_nothing_to_do')])
            @else
                @include('tools._push_summary')
            @endif

    </div>
@endsection
