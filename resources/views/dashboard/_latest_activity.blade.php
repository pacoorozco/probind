<div class="box box-info">
    <!-- box-header -->
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans('dashboard/messages.latest_activity') }}</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                        class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- ./ box-header -->
    <!-- box-body -->
    <div class="box-body no-padding">
        <table id="activity-table" class="table table-condensed">
            <thead>
            <tr>
                <th class="col-md-2">{{ trans('dashboard/messages.causer') }}</th>
                <th class="col-md-8">{{ trans('dashboard/messages.description') }}</th>
                <th class="col-md-2">{{ trans('dashboard/messages.created_at') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach($activityLog as $activity)
                <tr>
                    <td>{!! is_null($activity->causer) ? 'Unknown' : $activity->causer !!}</td>
                    <td>{!! $activity->description !!}</td>
                    <td>{{ $activity->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
            <tr>
                <th class="col-md-2">{{ trans('dashboard/messages.causer') }}</th>
                <th class="col-md-8">{{ trans('dashboard/messages.description') }}</th>
                <th class="col-md-2">{{ trans('dashboard/messages.created_at') }}</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- ./ box-body -->
    <!-- box-footer -->
    <div class="box-footer">
        <a href="{{-- route('tools.activity') --}}">
            <button type="button" class="btn btn-default pull-right">
                <i class="fa fa-eye"></i> {{ trans('dashboard/messages.more_activity') }}
            </button>
        </a>
    </div>
    <!-- ./ box-footer -->
</div>
