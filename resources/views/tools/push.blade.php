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
            <h3 class="box-title">Pushing DNS updates to the servers</h3>
        </div>
        <div class="box-body">
            <div class="callout callout-warning">
                <h4>Warning!</h4>

                <p>{{ trans('tools/messages.push_updates_warning') }}</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    These servers will be pushed:
                    <ul>
                        @forelse($servers as $server)
                            <li>{{ $server->hostname }}</li>
                        @empty
                            <li>None</li>
                        @endforelse
                    </ul>
                </div>
                <div class="col-md-4">
                    These zones will be deleted:
                    <ul>
                        @forelse($zonesToDelete as $zone)
                            <li>{{ $zone->domain }}</li>
                        @empty
                            <li>None</li>
                        @endforelse
                    </ul>
                </div>
                <div class="col-md-4">
                    These zones will be created / updated:
                    <ul>
                        @forelse($zonesToUpdate as $zone)
                            <li>{{ $zone->domain }}</li>
                        @empty
                            <li>None</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::open(['route' => 'tools.push_updates']) !!}

            <a href="{{ route('home') }}">
                <button type="button" class="btn btn-primary">
                    <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
                </button>
            </a>
            {!! Form::button('<i class="fa fa-download"></i> Push updates', array('type' => 'submit', 'class' => 'btn btn-warning pull-right')) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection