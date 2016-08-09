<div class="box box-solid box-danger">
    <div class="box-header">
        <h3 class="box-title">Delete this zone</h3>
    </div>
    <div class="box-body">
        This will delete this zone and all related information. Please be sure you want to do this. This action is dangerous.
    </div>
    <div class="box-footer">
        {!! Form::open(array('route' => array('zones.destroy', $zone), 'method' => 'delete', )) !!}
        {!! Form::button('<i class="fa fa-trash-o"></i>' . trans('general.delete'), ['type' => 'submit', 'class' => 'btn btn-danger pull-right']) !!}
        {!! Form::close() !!}
    </div>
</div>