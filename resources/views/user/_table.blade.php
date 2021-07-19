{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/datatables/dataTables.bootstrap.css') }}">
@endpush

<div class="container-fluid">
    <div class="table-responsive">
        <table id="users-table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>{{ __('user/table.username') }}</th>
                <th>{{ __('user/table.name') }}</th>
                <th>{{ __('user/table.email') }}</th>
                <th>{{ __('general.actions') }}</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>{{ __('user/table.username') }}</th>
                <th>{{ __('user/table.name') }}</th>
                <th>{{ __('user/table.email') }}</th>
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
