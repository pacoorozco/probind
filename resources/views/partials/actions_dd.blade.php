<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a href="{{ route($model . '.show', $id) }}" class="btn btn-xs btn-info" role="button"
       data-toggle="tooltip" data-placement="top" title="{{ __('general.show') }}">
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route($model . '.edit', $id) }}" class="btn btn-xs btn-primary" role="button"
       data-toggle="tooltip" data-placement="top" title="{{ __('general.edit') }}">
        <i class="fa fa-edit"></i>
    </a>
    <a href="{{ route($model . '.delete', $id) }}" class="btn btn-xs btn-danger" role="button"
       data-toggle="tooltip" data-placement="top" title="{{ __('general.delete') }}">
        <i class="fa fa-trash-o"></i>
    </a>
</div>
