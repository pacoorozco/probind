{!! Form::open(['route' => 'search.results', 'method' => 'GET', 'role' => 'search']) !!}

<div class="row">
    <div class="col-md-3">
        {!! Form::label('domain', trans('zone/model.domain'), array('class' => 'control-label')) !!}
        {!! Form::text('domain', isset($searchTerms['domain']) ? $searchTerms['domain'] : null, array('class' => 'form-control')) !!}
    </div>
    <div class="col-md-3">
        {!! Form::label('name', trans('record/model.name'), array('class' => 'control-label')) !!}
        {!! Form::text('name', isset($searchTerms['name']) ? $searchTerms['name'] : null, array('class' => 'form-control')) !!}
    </div>
    <div class="col-md-2">
        {!! Form::label('type', trans('record/model.type'), array('class' => 'control-label')) !!}
        {!! Form::select('type', $searchValidInputTypes, isset($searchTerms['type']) ? $searchTerms['type'] : 'ANY_TYPE', ['class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        {!! Form::label('data', trans('record/model.data'), array('class' => 'control-label')) !!}
        {!! Form::text('data', isset($searchTerms['data']) ? $searchTerms['data'] : null, array('class' => 'form-control')) !!}
    </div>
    <div class="col-md-1">
        {!! Form::label('submit', 'Search', array('class' => 'control-label')) !!}
        {!! Form::button('<i class="fa fa-search"></i> ' . trans('general.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
    </div>
</div>
{!! Form::close() !!}
