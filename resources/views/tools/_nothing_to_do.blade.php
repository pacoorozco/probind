<div class="box-body">
    <div class="callout callout-info">
        <h4>{{ trans('general.info') }}</h4>

        <p>{!! $info_message !!}</p>
    </div>
</div>
<div class="box-footer">
    <a href="{{ route('home') }}">
        <button type="button" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
        </button>
    </a>
</div>
