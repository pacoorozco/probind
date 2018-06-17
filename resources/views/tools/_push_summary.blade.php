<div class="box-body">
    <div class="callout callout-warning">
        <h4>{{ trans('general.warning') }}</h4>

        <p>{{ trans('tools/messages.push_updates_warning') }}</p>
    </div>
    <div class="row">
        <div class="col-md-4">
            These servers will be pushed:
            <ul>
                @foreach($servers as $server)
                    <li>{{ $server->hostname }}</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4">
            These zones will be deleted:
            <ul>
                @forelse($zonesToDelete as $zone)
                    <li>{{ $zone->domain }}</li>
                @empty
                    <li>None</li>
                @endforelse
            </ul>
        </div>
        <div class="col-md-4">
            These zones will be created / updated:
            <ul>
                @forelse($zonesToUpdate as $zone)
                    <li>{{ $zone->domain }}</li>
                @empty
                    <li>None</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
<div class="box-footer">
    <a href="{{ route('home') }}" class="btn btn-primary" role="button">
            <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
    </a>
    {!! Form::open(['route' => 'tools.push_updates']) !!}
    {!! Form::button('<i class="fa fa-download"></i> Push updates', array('type' => 'submit', 'class' => 'btn btn-warning pull-right')) !!}
    {!! Form::close() !!}
</div>
