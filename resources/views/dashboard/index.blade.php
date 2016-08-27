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

    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
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
            <!-- /.small-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
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
            <!-- /.small-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $data['records'] }}</h3>

                    <p>{{ trans('site.records') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bullseye"></i>
                </div>
                <a href="{{-- route('records.index') --}}" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
            <!-- /.small-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
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
            <!-- /.small-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- monthly records statistics -->
        <div class="col-md-8 col-sm-12 col-xs-12">
            {{-- @include('dashboard._monthly_records_stats') --}}
        </div>

        <!-- record types statistics -->
        <div class="col-md-4 col-sm-12 col-xs-12">
            {{-- @include('dashboard._record_types_stats') --}}
        </div>
    </div>
@endsection
