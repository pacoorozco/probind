<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a href="{{ route('zones.records.show', [$zone, $record]) }}" class="btn btn-xs btn-info" role="button"
       data-toggle="tooltip" data-placement="top" title="{{ trans('general.show') }}">
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route('zones.records.edit', [$zone, $record]) }}" class="btn btn-xs btn-primary" role="button"
       data-toggle="tooltip" data-placement="top" title="{{ trans('general.edit') }}">
        <i class="fa fa-edit"></i>
    </a>
    <a href="{{ route('zones.records.delete', [$zone, $record]) }}" class="btn btn-xs btn-danger" role="button"
       data-toggle="tooltip" data-placement="top" title="{{ trans('general.delete') }}">
        <i class="fa fa-trash-o"></i>
    </a>
</div>
