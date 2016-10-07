<div class="card blue-grey darken-1">
    <div class="card-content white-text">
        <hr>
        <div class="center-align">
            <p class="card-title">{{ trans('installer.welcome.name') }}</p>
            <p><em>{{ trans('installer.welcome.version') }}</em></p>
            <hr>
            <p class="card-title">{{ trans('installer.welcome.title') }}</p>
        </div>
        <p>{{ trans('installer.welcome.sub-title') }}</p>
        <ol>
            @for ($i = 1; $i < 5; $i++)
                <li>{{ trans('installer.welcome.item' . $i) }}</li>
            @endfor
        </ol>
        <p>{{ trans('installer.welcome.message') }}</p>
    </div>
    <div class="card-action">
        <a class="btn waves-effect waves-light" href="{{ url('install/database') }}">
            {{ trans('installer.welcome.button') }}
            <i class="material-icons right">send</i>
        </a>
    </div>
</div>