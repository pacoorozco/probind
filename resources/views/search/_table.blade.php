@if(count($records))
    <p>{{ __('search/messages.results_summary', ['totalItems' => $records->total(), 'firstItem' => $records->firstItem(), 'lastItem' => $records->lastItem()]) }}</p>
    <div class="container-fluid">
        <div class="table-responsive">
            <table id="search-results" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>{{ __('zone/table.domain') }}</th>
                    <th>{{ __('record/table.name') }}</th>
                    <th>{{ __('record/table.type') }}</th>
                    <th>{{ __('record/table.data') }}</th>
                    <th>{{ __('general.actions') }}</th>
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
                    <th>{{ __('zone/table.domain') }}</th>
                    <th>{{ __('record/table.name') }}</th>
                    <th>{{ __('record/table.type') }}</th>
                    <th>{{ __('record/table.data') }}</th>
                    <th>{{ __('general.actions') }}</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="pull-right">
        {{ $records->appends($searchTerms)->links() }}
    </div>



@else
    <div class="callout callout-info">
        <h4>{{ __('search/messages.no_results') }}</h4>
        <p>{{ __('search/messages.no_results_help') }}</p>
    </div>
@endif

