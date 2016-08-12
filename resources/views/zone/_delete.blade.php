<div class="box box-solid box-danger">
    <div class="box-header">
        <h3 class="box-title">{{ trans('zone/title.zone_delete') }}</h3>
    </div>
    <div class="box-body">
        {{ trans('zone/messages.delete.warning') }}
    </div>
    <div class="box-footer">
        {!! Form::open(array('route' => array('zones.destroy', $zone), 'method' => 'delete', )) !!}
        {!! Form::button('<i class="fa fa-trash-o"></i> ' . trans('general.delete'), ['type' => 'submit', 'class' => 'btn btn-danger pull-right']) !!}
        {!! Form::close() !!}
    </div>
</div>