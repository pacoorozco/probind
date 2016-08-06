<table id="zones-table" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th class="col-md-4">{{ trans('zone/table.domain') }}</th>
        <th class="col-md-4">{{ trans('zone/table.master') }}</th>
        <th class="col-md-2">{{ trans('zone/table.updated') }}</th>
        <th class="col-md-2">{{ trans('general.actions') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($zones as $zone)
        <tr>
            <td>{{ $zone->domain }}</td>
            <td>{{ $zone->master }}</td>
            <td>{{ $zone->updated }}</td>
            <td>
                @include('partials.actions_dd', ['model' => 'zones', 'id' => $zone->id])
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th class="col-md-4">{{ trans('zone/table.domain') }}</th>
        <th class="col-md-4">{{ trans('zone/table.master') }}</th>
        <th class="col-md-2">{{ trans('zone/table.updated') }}</th>
        <th class="col-md-2">{{ trans('general.actions') }}</th>
    </tr>
    </tfoot>
</table>