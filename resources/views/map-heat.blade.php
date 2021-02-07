@extends('layout')

<link href="{{ mix('css/map-heat.css') }}" rel="stylesheet">

@section('content')
    <div class="container">
        <div class="m-5">
            <h1>Карта кликов: {{ $url }}</h1>

            <p>
                Клики выводятся без сортировки по времени, но с разделением по ширине экрана,<br>
                т.к. это напрямую влияет на позиционирование точек клика на странице.
            </p>
            <div class="width-group">
                <div>
                    <p>Ширина:</p>
                </div>

                @foreach($widthGroups as $id => $group)
                    <div>
                        <input
                                type="radio"
                                name="width-group" id="{{ $group }}"
                                value="{{ $group }}"
                                {{ $id === 0 ? 'checked="checked"' : '' }}
                        />
                        <label for="{{ $group }}">
                            {{ $group }}
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="options">
                <div>
                    <label>Радиус</label>
                    <input type="range" id="radius" value="15" min="10" max="50"><br>
                </div>
                <div>
                    <label>Размытие</label>
                    <input type="range" id="blur" value="10" min="10" max="50">
                </div>
            </div>


            <div class="iframe-wrap" style="height: {{ $maxY }}px">
                <canvas
                        id="canvas"
                        width="{{ floor($width/2) }}"
                        height="{{ $maxY }}"
                >
                </canvas>

                <div
                        class="iframe"
                        style="width: {{ floor($width/2) }}px; height: {{ $maxY }}px"
                >
                    <iframe src="http://{{ $url }}"
                            scrolling="no" seamless="seamless" frameborder="0"
                            width="{{ $width }}">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <script>{!! $jsData !!}</script>

    <script src="{{ mix('js/simpleheat.js') }}"></script>
    <script src="{{ mix('js/map-heat.js') }}"></script>
@endsection


