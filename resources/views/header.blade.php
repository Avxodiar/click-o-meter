<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="{{ route('home') }}">#Click-o-Meter</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item @if(Route::is('test1')) active @endif">
                <a class="nav-link" href="{{ route('test1') }}">Test 1<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item @if(Route::is('test2')) active @endif">
                <a class="nav-link" href="{{ route('test2') }}">Test 2</a>
            </li>
            <li class="nav-item @if(Route::is('graph')) active @endif">
                <a class="nav-link" href="{{ route('graph') }}">Графики кликов</a>
            </li>
            <li class="nav-item @if(Route::is('map')) active @endif">
                <a class="nav-link" href="{{ route('map') }}">Карты кликов</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="{{ route('map') }}" tabindex="-1" aria-disabled="true">Reserved</a>
            </li>
        </ul>
    </div>
</nav>
