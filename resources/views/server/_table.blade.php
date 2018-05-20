{{-- Styles --}}
@push('styles')
{!! HTML::style('vendor/admin-lte/plugins/datatables/dataTables.bootstrap.css') !!}
@endpush

<table id="servers-table" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th class="col-md-4">{{ trans('server/table.hostname') }}</th>
        <th class="col-md-3">{{ trans('server/table.ip_address') }}</th>
        <th class="col-md-1">{{ trans('server/table.type') }}</th>
        <th class="col-md-1">{{ trans('server/table.push_updates') }}</th>
        <th class="col-md-1">{{ trans('server/table.ns_record') }}</th>
        <th class="col-md-2">{{ trans('general.actions') }}</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th class="col-md-4">{{ trans('server/table.hostname') }}</th>
        <th class="col-md-3">{{ trans('server/table.ip_address') }}</th>
        <th class="col-md-1">{{ trans('server/table.type') }}</th>
        <th class="col-md-1">{{ trans('server/table.push_updates') }}</th>
        <th class="col-md-1">{{ trans('server/table.ns_record') }}</th>
        <th class="col-md-2">{{ trans('general.actions') }}</th>
    </tr>
    </tfoot>
</table>

{{-- Scripts --}}
@push('scripts')
{!! HTML::script('vendor/admin-lte/plugins/datatables/jquery.dataTables.min.js') !!}
{!! HTML::script('vendor/admin-lte/plugins/datatables/dataTables.bootstrap.min.js') !!}

<script>
    $(function () {
        $('#servers-table').DataTable({
            "ajax": "{{ route('servers.data') }}",
            "columns": [
                {data: "hostname"},
                {data: "ip_address"},
                {data: "type"},
                {data: "push_updates", "orderable": false, "searchable": false},
                {data: "ns_record", "orderable": false, "searchable": false},
                {data: "actions", "orderable": false, "searchable": false}
            ],
            "aLengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "{{ trans('general.all') }}"]
            ],
            // set the initial value
            "iDisplayLength": 10
        });
    });
</script>
@endpush
