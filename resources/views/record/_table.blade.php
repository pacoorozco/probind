{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/datatables/dataTables.bootstrap.css') }}">
@endpush

<div class="container-fluid">
    <div class="table-responsive">
        <table id="records-table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>{{ __('record/table.name') }}</th>
                <th>{{ __('record/table.type') }}</th>
                <th>{{ __('record/table.data') }}</th>
                <th>{{ __('record/table.ttl') }}</th>
                <th>{{ __('general.actions') }}</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>{{ __('record/table.name') }}</th>
                <th>{{ __('record/table.type') }}</th>
                <th>{{ __('record/table.data') }}</th>
                <th>{{ __('record/table.ttl') }}</th>
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
            $('#records-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('zones.records.data', $zone->id) }}",
                "columns": [
                    {data: "name"},
                    {data: "type"},
                    {data: "data"},
                    {data: "ttl", "orderable": false, "searchable": false},
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
