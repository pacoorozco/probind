{{-- Styles --}}
@push('styles')
{!! HTML::style('themes/admin-lte/plugins/datatables/dataTables.bootstrap.css') !!}
@endpush

<table id="records-table" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th class="col-md-4">{{ trans('record/table.name') }}</th>
        <th class="col-md-1">{{ trans('record/table.ttl') }}</th>
        <th class="col-md-1">{{ trans('record/table.type') }}</th>
        <th class="col-md-4">{{ trans('record/table.data') }}</th>
        <th class="col-md-2">{{ trans('general.actions') }}</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th class="col-md-4">{{ trans('record/table.name') }}</th>
        <th class="col-md-1">{{ trans('record/table.ttl') }}</th>
        <th class="col-md-1">{{ trans('record/table.type') }}</th>
        <th class="col-md-4">{{ trans('record/table.data') }}</th>
        <th class="col-md-2">{{ trans('general.actions') }}</th>
    </tr>
    </tfoot>
</table>

{{-- Scripts --}}
@push('scripts')
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    {{-- !! HTML::script('themes/admin-lte/plugins/datatables/jquery.dataTables.min.js') !! --}}
    {{-- !! HTML::script('themes/admin-lte/plugins/datatables/dataTables.bootstrap.min.js') !! --}}

<script>
    $(function () {
        $('#records-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('zones.records.data', $zone->id) }}",
            "columns": [
                {data: "name"},
                {data: "ttl", "orderable": false, "searchable": false},
                {data: "type"},
                {data: "data"},
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
