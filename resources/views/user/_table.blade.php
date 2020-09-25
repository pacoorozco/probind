{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/datatables/dataTables.bootstrap.css') }}">
@endpush

<table id="users-table" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th class="col-md-4">{{ __('user/table.username') }}</th>
        <th class="col-md-3">{{ __('user/table.name') }}</th>
        <th class="col-md-3">{{ __('user/table.email') }}</th>
        <th class="col-md-2">{{ __('general.actions') }}</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th class="col-md-4">{{ __('user/table.username') }}</th>
        <th class="col-md-3">{{ __('user/table.name') }}</th>
        <th class="col-md-3">{{ __('user/table.email') }}</th>
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
        $('#users-table').DataTable({
            "ajax": "{{ route('users.data') }}",
            "columns": [
                {data: "username"},
                {data: "name"},
                {data: "email"},
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
