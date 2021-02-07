@extends('layout')

@section('content')
    <div class="container">
        <div class="m-5">
            <h1>Карты кликов</h1>

            <p>
                Для упрощения показывается только 1ый сайт в БД.<br>
                При необходимости можно сделать динамический выбор сайта и ссылок.
            </p>

            <h2>Список ссылок для сайта: {{ $siteName }}</h2>

            @if (!empty($links))
                <ul>
                @foreach($links as $link)
                    <li>
                        <a href="{{ route('map-link', $link['id']) }}">
                            {{ $siteName . $link['link'] }}
                        </a>
                    </li>
                @endforeach
                </ul>
            @else
                <p>Для данного сайта нет данных об остлеживаемых ссылках</p>
            @endif

        </div>
    </div>
@endsection
