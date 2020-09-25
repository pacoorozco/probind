{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/datatables/dataTables.bootstrap.css') }}">
@endpush

<table id="zones-table" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th class="col-md-4">{{ __('zone/table.domain') }}</th>
        <th class="col-md-1">{{ __('zone/table.type') }}</th>
        <th class="col-md-4">{{ __('zone/table.master_server') }}</th>
        <th class="col-md-1">{{ __('zone/table.has_modifications') }}</th>
        <th class="col-md-2">{{ __('general.actions') }}</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th class="col-md-4">{{ __('zone/table.domain') }}</th>
        <th class="col-md-1">{{ __('zone/table.type') }}</th>
        <th class="col-md-4">{{ __('zone/table.master_server') }}</th>
        <th class="col-md-1">{{ __('zone/table.has_modifications') }}</th>
        <th class="col-md-2">{{ __('general.actions') }}</th>
    </tr>
    </tfoot>
</table>

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
                {data: "master_server"},
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
