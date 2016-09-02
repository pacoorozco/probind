@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('site.dashboard') }} :: @parent
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

@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- info boxes -->
    <div class="row" id="info-boxes">
        <!-- servers box -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $data['servers'] }}</h3>

                    <p>{{ trans('site.servers') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-server"></i>
                </div>
                <a href="{{ route('servers.index') }}" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./ servers-box -->
        <!-- zones box -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $data['zones'] }}</h3>

                    <p>{{ trans('site.zones') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-database"></i>
                </div>
                <a href="{{ route('zones.index') }}" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./ zones-box -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <!-- records box -->
        <div class="col-md-3 col-sm-6 col-xs-12">

            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $data['records'] }}</h3>

                    <p>{{ trans('site.records') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bullseye"></i>
                </div>
                <a href="{{  route('search.index') }}" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./ records-box -->
        <!-- users box -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $data['users'] }}</h3>

                    <p>{{ trans('site.users') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="{{-- route('users.index') --}}" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./ users-box -->
    </div>
    <!-- ./ info boxes -->

    <!-- statistics -->
    <div class="row">
        <!-- monthly records statistics -->
        <div class="col-md-8 col-sm-12 col-xs-12" id="monthly-stats-widget">
            {{-- @include('dashboard._monthly_records_stats') --}}
        </div>

        <!-- record types statistics -->
        <div class="col-md-4 col-sm-12 col-xs-12" id="record-stats-widget">
            {{-- @include('dashboard._record_types_stats') --}}
        </div>
    </div>
    <!-- ./ statistics -->

    <!-- log activity -->
    <div class="row">
        <!-- latest activity widget -->
        <div class="col-md-8 col-sm-12 col-xs-12" id="latest-activity-widget">
            @include('dashboard._latest_activity')
        </div>
        <!-- ./ latest activity widget -->

        <!-- latest push updates widget -->
        <div class="col-md-4 col-sm-12 col-xs-12" id="latest-jobs-widget">
            @include('dashboard._latest_jobs')
        </div>
        <!-- ./ latest push updates widget -->
    </div>
    <!-- log activity -->

@endsection
