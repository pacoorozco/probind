@if(count($records))
    <p>{{ __('search/messages.results_summary', ['totalItems' => $records->total(), 'firstItem' => $records->firstItem(), 'lastItem' => $records->lastItem()]) }}</p>
    <table id="search-results" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th class="col-md-2">{{ __('zone/table.domain') }}</th>
            <th class="col-md-3">{{ __('record/table.name') }}</th>
            <th class="col-md-1">{{ __('record/table.type') }}</th>
            <th class="col-md-4">{{ __('record/table.data') }}</th>
            <th class="col-md-2">{{ __('general.actions') }}</th>
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
            <th class="col-md-2">{{ __('zone/table.domain') }}</th>
            <th class="col-md-3">{{ __('record/table.name') }}</th>
            <th class="col-md-1">{{ __('record/table.type') }}</th>
            <th class="col-md-4">{{ __('record/table.data') }}</th>
            <th class="col-md-2">{{ __('general.actions') }}</th>
        </tr>
        </tfoot>
    </table>
    <div class="pull-right">
        {{ $records->appends($searchTerms)->links() }}
    </div>



@else
    <div class="callout callout-info">
        <h4>{{ __('search/messages.no_results') }}</h4>
        <p>{{ __('search/messages.no_results_help') }}</p>
    </div>
@endif

