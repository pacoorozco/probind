@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('search/title.search_for_records') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('search/title.search_for_records') }}
    <small>{{ trans('search/title.search_for_records_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li class="active">
        {{ trans('site.search') }}
    </li>
@endsection


{{-- Content --}}
@section('content')
    <!-- search criteria -->
    @include('search._form')
    <!-- ./ search criteria -->

    @if(isset($records))
        <!-- search results -->
        <div class="box">
            <!-- box-header -->
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('search/title.search_results') }}</h3>
            </div>
            <!-- ./ box-header -->
            <div class="box-body">
                @include('search._table')
            </div>
        </div>
        <!-- ./ search results -->
    @endif
@endsection
