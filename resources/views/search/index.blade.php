@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ __('search/title.search_for_records') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ __('search/title.search_for_records') }}
    <small>{{ __('search/title.search_for_records_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ __('site.dashboard') }}
        </a>
    </li>
    <li class="active">
        {{ __('site.search') }}
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
                <h3 class="box-title">{{ __('search/title.search_results') }}</h3>
            </div>
            <!-- ./ box-header -->
            <div class="box-body">
                @include('search._table')
            </div>
        </div>
        <!-- ./ search results -->
    @endif
@endsection
