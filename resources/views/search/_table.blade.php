@if(count($records))
    <p>{{ trans('search/messages.results_summary', ['totalItems' => $records->total(), 'firstItem' => $records->firstItem(), 'lastItem' => $records->lastItem()]) }}</p>
    <table id="search-results" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th class="col-md-2">{{ trans('zone/table.domain') }}</th>
            <th class="col-md-3">{{ trans('record/table.name') }}</th>
            <th class="col-md-1">{{ trans('record/table.type') }}</th>
            <th class="col-md-4">{{ trans('record/table.data') }}</th>
            <th class="col-md-2">{{ trans('general.actions') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach($records as $record)
            <tr>
                <td>
                    <a href="{{ route('zones.records.index', $record->zone) }}">{{ $record->zone->domain }}</a>
                </td>
                <td>{{ $record->name }}</td>
                <td>{{ $record->type }}</td>
                <td>{{ $record->data }}</td>
                <td>
                    @include('record/_actions', ['zone' => $record->zone, 'record' => $record])
                </td>
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        <tr>
            <th class="col-md-2">{{ trans('zone/table.domain') }}</th>
            <th class="col-md-3">{{ trans('record/table.name') }}</th>
            <th class="col-md-1">{{ trans('record/table.type') }}</th>
            <th class="col-md-4">{{ trans('record/table.data') }}</th>
            <th class="col-md-2">{{ trans('general.actions') }}</th>
        </tr>
        </tfoot>
    </table>
    <div class="pull-right">
        {{ $records->appends($searchTerms)->links() }}
    </div>



@else
    <div class="callout callout-info">
        <h4>{{ trans('search/messages.no_results') }}</h4>
        <p>{{ trans('search/messages.no_results_help') }}</p>
    </div>
@endif

