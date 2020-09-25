<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a href="{{ route('zones.show', $zone) }}" class="btn btn-xs btn-info" role="button"
       data-toggle="tooltip" data-placement="top" title="{{ __('general.show') }}">
        <i class="fa fa-eye"></i>
    </a>

    @if($zone->isMasterZone())
        <a href="{{ route('zones.records.index', $zone) }}" class="btn btn-xs btn-primary" role="button"
           data-toggle="tooltip" data-placement="top" title="{{ __('record/title.view_records') }}">
            <i class="fa fa-database"></i>
        </a>
        <a href="{{ route('zones.records.create', $zone) }}" class="btn btn-xs btn-success" role="button"
           data-toggle="tooltip" data-placement="top" title="{{ __('record/title.create_new') }}">
            <i class="fa fa-plus"></i>
        </a>
    @endif
</div>
