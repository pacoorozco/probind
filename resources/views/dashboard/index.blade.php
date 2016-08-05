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
        <!-- Info boxes -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-trophy"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Test 1</span>
                    <span class="info-box-number">11</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-comments"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Test 2</span>
                    <span class="info-box-number">22</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-bank"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Test 3</span>
                    <span class="info-box-number">33</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Test 4</span>
                    <span class="info-box-number">44</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
