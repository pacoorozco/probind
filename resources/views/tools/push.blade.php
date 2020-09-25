@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('tools/title.push_updates') }} @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('tools/title.push_updates') }}
    <small>{{ __('tools/title.push_updates_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ __('site.dashboard') }}
        </a>
    </li>
    <li>
        <a href="{{-- route('tools.index') --}}">
            {{ __('site.tools') }}
        </a>
    </li>
    <li class="active">
        {{ __('tools/title.push_updates') }}
    </li>
@endsection

{{-- Content --}}
@section('content')
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('tools/title.pushing_updates') }}</h3>
        </div>

            @if($servers->isEmpty())
                @include('tools._nothing_to_do', ['info_message' => __('tools/messages.push_updates_no_servers')])
            @elseif($zonesToUpdate->isEmpty() && $zonesToDelete->isEmpty())
                @include('tools._nothing_to_do', ['info_message' => __('tools/messages.push_updates_nothing_to_do')])
            @else
                @include('tools._push_summary')
            @endif

    </div>
@endsection
