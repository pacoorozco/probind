<div class="box box-solid box-info">
    <div class="box-header">
        <h3 class="box-title">{{ __('zone/title.resource_records') }}</h3>
    </div>
    <div class="box-body">
        {{ $zone->present()->recordCount }}
    </div>
    <div class="box-footer">
        <div class="pull-right">
            <a href="{{ route('zones.records.index', $zone) }}" class="btn btn-info" role="button">
                <i class="fa fa-database"></i> {{ __('record/title.view_records') }}
            </a>
            <a href="{{ route('zones.records.create', $zone) }}" class="btn btn-success" role="button">
                <i class="fa fa-plus"></i> {{ __('record/title.create_new') }}
            </a>
        </div>
    </div>
</div>
