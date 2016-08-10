<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a href="{{ route('zones.records.show', [$zone, $record]) }}">
        <button type="button" class="btn btn-xs btn-info"
                data-toggle="tooltip" data-placement="top" title="{{ trans('general.show') }}"><i
                    class="fa fa-eye"></i>
        </button>
    </a>
    <a href="{{ route('zones.records.edit', [$zone, $record]) }}">
        <button type="button" class="btn btn-xs btn-primary"
                data-toggle="tooltip" data-placement="top" title="{{ trans('general.edit') }}"><i
                    class="fa fa-edit"></i>
        </button>
    </a>
    <a href="{{ route('zones.records.delete', [$zone, $record]) }}">
        <button type="button" class="btn btn-xs btn-danger"
                data-toggle="tooltip" data-placement="top" title="{{ trans('general.delete') }}"><i
                    class="fa fa-trash-o"></i>
        </button>
    </a>
</div>