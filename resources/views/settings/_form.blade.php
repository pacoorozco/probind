{{-- Edit Settings Form --}}
{!! Form::open(['route' => ['settings.update'], 'method' => 'put']) !!}

<div class="nav-tabs-custom">

    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#panel_settings_tab1" data-toggle="tab">
                {{ trans('settings/title.zone_settings') }}
            </a>
        </li>
        <li>
            <a href="#panel_settings_tab2" data-toggle="tab">
                {{ trans('settings/title.ssh_settings') }}
            </a>
        </li>
    </ul>

    <div class="tab-content">

        <!-- Zone global settings panel -->
        <div class="tab-pane active" id="panel_settings_tab1">
            @include('settings._zone_settings')
        </div>
        <!-- ./ Zone global settings panel -->

        <!-- SSH global settings panel -->
        <div class="tab-pane" id="panel_settings_tab2">
            @include('settings._ssh_settings')
        </div>
        <!-- ./ SSH global settings panel -->

    </div>
</div>

<!-- Form Actions -->
{!! Form::button('<i class="fa fa-floppy-o"></i> ' . trans('general.save'), array('id' => 'submitButton', 'type' => 'submit', 'class' => 'btn btn-success')) !!}
<!-- ./ form actions -->
{!! Form::close() !!}

@push('scripts')
<script>
    $('#submitButton').click(function () {
        $('input:invalid').each(function () {
            // Find the tab-pane that this element is inside, and get the id
            var $closest = $(this).closest('.tab-pane');
            var id = $closest.attr('id');

            // Find the link that corresponds to the pane and have it show
            $('.nav a[href="#' + id + '"]').tab('show');

            // Only want to do it once
            return false;
        });
    });
</script>
@endpush
