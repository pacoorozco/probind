{{-- Styles --}}
@push('styles')
{!! HTML::style('vendor/AdminLTE/plugins/datatables/dataTables.bootstrap.css') !!}
@endpush

<table id="users-table" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th class="col-md-4">{{ trans('user/table.username') }}</th>
        <th class="col-md-3">{{ trans('user/table.name') }}</th>
        <th class="col-md-3">{{ trans('user/table.email') }}</th>
        <th class="col-md-2">{{ trans('general.actions') }}</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th class="col-md-4">{{ trans('user/table.username') }}</th>
        <th class="col-md-3">{{ trans('user/table.name') }}</th>
        <th class="col-md-3">{{ trans('user/table.email') }}</th>
        <th class="col-md-2">{{ trans('general.actions') }}</th>
    </tr>
    </tfoot>
</table>

{{-- Scripts --}}
@push('scripts')
{!! HTML::script('vendor/AdminLTE/plugins/datatables/jquery.dataTables.min.js') !!}
{!! HTML::script('vendor/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js') !!}

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
                [5, 10, 15, 20, "{{ trans('general.all') }}"]
            ],
            // set the initial value
            "iDisplayLength": 10
        });
    });
</script>
@endpush
