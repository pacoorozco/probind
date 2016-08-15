<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a href="{{ route('zones.show', $zone) }}">
        <button type="button" class="btn btn-xs btn-info"
                data-toggle="tooltip" data-placement="top" title="{{ trans('general.show') }}"><i
                    class="fa fa-eye"></i>
        </button>
    </a>

    @if($zone->isMasterZone())
        <a href="{{ route('zones.records.index', $zone) }}">
            <button type="button" class="btn btn-xs btn-primary"
                    data-toggle="tooltip" data-placement="top" title="{{ trans('record/title.view_records') }}"><i
                        class="fa fa-database"></i>
            </button>
        </a>
        <a href="{{ route('zones.records.create', $zone) }}">
            <button type="button" class="btn btn-xs btn-success"
                    data-toggle="tooltip" data-placement="top" title="{{ trans('record/title.create_new') }}"><i
                        class="fa fa-plus"></i>
            </button>
        </a>
    @endif
</div>
