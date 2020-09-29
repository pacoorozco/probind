<footer class="main-footer">
    <div class="pull-right hidden-xs">
        Professional DNS management made easy.
    </div>
    <strong>Copyright &copy;
        2016 - {{ date("Y") }} {!! HTML::link('http://pacoorozco.info', 'Paco Orozco', ['rel' => 'nofollow']) !!}</strong>. Powered
    by {!! HTML::link('https://github.com/pacoorozco/probind', 'ProBIND', ['rel' => 'nofollow']) !!}
    v{{ Config::get('app.version') }}
</footer>
