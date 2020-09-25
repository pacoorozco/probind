<div class="box box-info">
    <!-- box-header -->
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('dashboard/messages.latest_activity') }}</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                    class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- ./ box-header -->
    <!-- box-body -->
    <div class="box-body table-responsive no-padding">
        <table id="activity-table" class="table table-hover">
            <thead>
            <tr>
                <th class="col-md-1"><i class="fa fa-fw fa-database"></i> {{ __('dashboard/messages.id') }}</th>
                <th class="col-md-2"><i class="fa fa-fw fa-clock-o"></i> {{ __('dashboard/messages.created_at') }}</th>
                <th class="col-md-7"><i class="fa fa-fw fa-file-text-o"></i> {{ __('dashboard/messages.description') }}
                </th>
                <th class="col-md-2"><i class="fa fa-fw fa-user"></i> {{ __('dashboard/messages.causer') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach($activityLog as $activity)
                <tr>
                    <td>{{ $activity->id }}</td>
                    <td>{{ $activity->created_at }}</td>
                    <td>{!! $activity->description !!}</td>
                    <td>{!! is_null($activity->causer) ? "system" : $activity->causer->username !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- ./ box-body -->
    <!-- box-footer -->
    <div class="box-footer">
        <a href="{{-- route('tools.activity') --}}" class="btn btn-default pull-right" role="button">
            <i class="fa fa-eye"></i> {{ __('dashboard/messages.more_activity') }}
        </a>
    </div>
    <!-- ./ box-footer -->
</div>
