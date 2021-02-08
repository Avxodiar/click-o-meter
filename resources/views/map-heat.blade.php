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

                @foreach($widthLinks as $id => $group)
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
                    <label for="radius">Радиус</label>
                    <input type="range" id="radius" value="15" min="10" max="50"><br>
                </div>
                <div>
                    <label for="blur">Размытие</label>
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

            <div class="mt-5">
                <small>
                    <b>Примечание:</b><br>
                    <p>
                        Карта кликов отображается в Canvas под которым спозиционирован iframe
                        c контентом отслеживаемого сайта.<br>
                        Плюсы этого метода - при наличии динамических элементов (например слайдер-карусель)
                        можно легко понять группы кликов в местах на которых ничего нет лишь в определенный
                        момент времени. Минус данного способа - при сохранении canvas`а будет сохранена только
                        сама карта кликов без снимка сайта.
                    </p>
                    <p>Альтернативный вариант - предварительная загрузка в Canvas снимка сайта,
                        выполненного каким-либо сервисом. Это позволит сохранять карту кликов в файл.
                        Минус данного способа - анализировать страницы с динамичным содержанием будет сложнее.
                        При необходимости им воспользоваться создан класс-заготовка по созданию снимков App\Support\Screen
                    </p>
                </small>
            </div>
        </div>
    </div>

    <script>{!! $jsData !!}</script>

    <script src="{{ mix('js/simpleheat.js') }}"></script>
    <script src="{{ mix('js/map-heat.js') }}"></script>
@endsection


