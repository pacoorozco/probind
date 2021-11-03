{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/datatables/dataTables.bootstrap.css') }}">
@endpush

<div class="container-fluid">
    <div class="table-responsive">
        <table id="zones-table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>{{ __('zone/table.domain') }}</th>
                <th>{{ __('zone/table.type') }}</th>
                <th>{{ __('zone/table.server') }}</th>
                <th>{{ __('zone/table.records_count') }}</th>
                <th>{{ __('zone/table.has_modifications') }}</th>
                <th>{{ __('general.actions') }}</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>{{ __('zone/table.domain') }}</th>
                <th>{{ __('zone/table.type') }}</th>
                <th>{{ __('zone/table.server') }}</th>
                <th>{{ __('zone/table.records_count') }}</th>
                <th>{{ __('zone/table.has_modifications') }}</th>
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
            $('#zones-table').DataTable({
                "ajax": "{{ route('zones.data') }}",
                "columns": [
                    {data: "domain"},
                    {data: "type"},
                    {data: "server"},
                    {data: "records_count", "searchable": false},
                    {data: "has_modifications", "orderable": false, "searchable": false},
                    {data: "actions", "orderable": false, "searchable": false}
                ],
                "order": [[1, 'asc'], [0, 'asc']],
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
