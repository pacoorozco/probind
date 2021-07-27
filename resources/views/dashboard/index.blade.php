@extends('layouts.admin')

{{-- Web site Title --}}
@section('title', __('site.dashboard'))

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
                    <h3>{{ $serversCount }}</h3>
                    <p>{{ __('site.servers') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-server"></i>
                </div>
                <a href="{{ route('servers.index') }}" class="small-box-footer">
                    Manage servers <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./ servers-box -->
        <!-- zones box -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $zonesCount }}</h3>
                    <p>{{ __('site.zones') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-database"></i>
                </div>
                <a href="{{ route('zones.index') }}" class="small-box-footer">
                    Manage zones <i class="fa fa-arrow-circle-right"></i>
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
                    <h3>{{ $resourceRecordsCount }}</h3>
                    <p>{{ __('site.records') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bullseye"></i>
                </div>
                <a href="{{  route('search.index') }}" class="small-box-footer">
                    Search records <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./ records-box -->
        <!-- users box -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $usersCount }}</h3>
                    <p>{{ __('site.users') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="{{ route('users.index') }}" class="small-box-footer">
                    Manage users <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./ users-box -->
    </div>
    <!-- ./ info boxes -->

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
