{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/datatables/dataTables.bootstrap.css') }}">
@endpush

<div class="container-fluid">
    <div class="table-responsive">
        <table id="servers-table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>{{ __('server/table.hostname') }}</th>
                <th>{{ __('server/table.ip_address') }}</th>
                <th>{{ __('server/table.type') }}</th>
                <th>{{ __('server/table.push_updates') }}</th>
                <th>{{ __('server/table.ns_record') }}</th>
                <th>{{ __('general.actions') }}</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>{{ __('server/table.hostname') }}</th>
                <th>{{ __('server/table.ip_address') }}</th>
                <th>{{ __('server/table.type') }}</th>
                <th>{{ __('server/table.push_updates') }}</th>
                <th>{{ __('server/table.ns_record') }}</th>
                <th>{{ __('general.actions') }}</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Scripts --}}
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/AdminLTE/datatables/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/AdminLTE/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(function () {
            $('#servers-table').DataTable({
                "ajax": "{!! route('servers.data') !!}",
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
                    [5, 10, 15, 20, "{{ __('general.all') }}"]
                ],
                // set the initial value
                "iDisplayLength": 10
            });
        });
    </script>
@endpush
