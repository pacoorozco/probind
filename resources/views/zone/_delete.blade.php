<div class="box box-solid box-danger">
    <div class="box-header">
        <h3 class="box-title">{{ __('zone/title.zone_delete') }}</h3>
    </div>
    <div class="box-body">
        {{ __('zone/messages.delete.warning') }}
    </div>
    <div class="box-footer">
        <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#deleteConfirmation">
            <i class="fa fa-trash-o"></i> {{ __('general.delete') }}
        </button>
    </div>
</div>

{!! Form::open(['route' => array('zones.destroy', $zone), 'method' => 'delete']) !!}
<!-- deleteConfirmation modal -->
<div class="modal fade" id="deleteConfirmation">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ __('general.absolutely_sure_question') }}</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <p>{{ __('general.unexpected_things_warning') }}</p>
                </div>
                <p>{!! __('general.action_cannot_be_undone') !!}</p>
                <p>{!! __('zone/messages.delete.confirmation', ['domain' => $zone->domain]) !!}</p>
                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('domainInput', __('general.please_confirm_to_delete', ['item'=>'zone']), ['class' => 'control-label']) !!}
                        {!! Form::text('domainInput', null, ['class' => 'form-control', 'id' => 'domainInput']) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button(__('general.i_understand_consequences', ['item' => 'zone']), ['type' => 'submit', 'id' => 'submitBtn', 'class' => 'btn btn-danger btn-block', 'disabled' => 'disabled']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ./ deleteConfirmation modal -->
{!! Form::close() !!}

@push('scripts')
<script>
    $(function () {
        var inputBox = $("#domainInput");
        var matchingText = "{{ $zone->domain }}";
        var outputButton = $("#submitBtn");

        inputBox.keyup(function (e) {
            if (inputBox.val().trim() === matchingText) {
                outputButton.removeAttr("disabled");
            } else {
                outputButton.attr("disabled", "true");
            }
        });
    });
</script>
@endpush

