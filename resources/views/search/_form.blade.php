{!! Form::open(['route' => 'search.results', 'method' => 'GET', 'role' => 'search']) !!}
<div class="box box-primary">
    <!-- box-header -->
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('search/title.search_criteria') }}</h3>
        <!-- box-tools -->
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
        <!-- ./ box-tools -->
    </div>
    <!-- ./ box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-3">
                {!! Form::label('domain', __('zone/model.domain'), array('class' => 'control-label')) !!}
                {!! Form::text('domain', isset($searchTerms['domain']) ? $searchTerms['domain'] : null, array('class' => 'form-control')) !!}
            </div>
            <div class="col-md-3">
                {!! Form::label('name', __('record/model.name'), array('class' => 'control-label')) !!}
                {!! Form::text('name', isset($searchTerms['name']) ? $searchTerms['name'] : null, array('class' => 'form-control')) !!}
            </div>
            <div class="col-md-3">
                {!! Form::label('type', __('record/model.type'), array('class' => 'control-label')) !!}
                {!! Form::select('type', $searchValidInputTypes, isset($searchTerms['type']) ? $searchTerms['type'] : 'ANY_TYPE', ['class' => 'form-control']) !!}
            </div>
            <div class="col-md-3">
                {!! Form::label('data', __('record/model.data'), array('class' => 'control-label')) !!}
                {!! Form::text('data', isset($searchTerms['data']) ? $searchTerms['data'] : null, array('class' => 'form-control')) !!}
            </div>
        </div>
    </div>
    <div class="box-footer">
        {!! __('search/messages.wildcard_help') !!}
        {!! Form::button('<i class="fa fa-search"></i> ' . __('general.search'), array('type' => 'submit', 'class' => 'btn btn-primary pull-right')) !!}
    </div>
</div>
{!! Form::close() !!}
