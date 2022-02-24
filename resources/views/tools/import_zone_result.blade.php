@extends('layouts.admin')

{{-- Web site Title --}}
@section('title', __('tools/title.push_updates'))

{{-- Content Header --}}
@section('header')
    {{ __('tools/title.import_zone') }}
    <small>{{ __('tools/title.import_zone_subtitle') }}</small>
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
        {{ __('tools/title.import_zone') }}
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('tools/title.import_zone') }}</h3>
        </div>

        <div class="box-body">
            <div class="form-group">
                <textarea class="form-control" rows="15" id="output" disabled>{{ $output }}</textarea>
            </div>
        </div>

        <div class="box-footer">
            <a href="{{ route('home') }}" class="btn btn-primary pull-right" role="button">
                {{ __('general.done') }} <i class="fa fa-arrow-right"></i>
            </a>
        </div>

    </div>
@endsection

{{-- Scripts --}}
@push('scripts')
    <script>
        $(function () {
            var $textarea = $('#output');
            $textarea.scrollTop($textarea[0].scrollHeight);
        });
    </script>
@endpush

