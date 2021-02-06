@extends('layout')

<style href="{{ mix('css/chart.min.css') }}"  rel="stylesheet"></style>

@section('content')
    <div class="container">
        <div class="m-5">
            <h1>График кликов</h1>

            <p>
                Для упрощения показывается только 1ый сайт(текущий) в БД.<br>
                При необходимости можно сделать динамический выбор сайта и ссылок.
            </p>
            <p>
                В качестве примера, по умолчанию, отображаются клики 5 первых ссылок в БД (при наличии на них кликов).
                Для тестирования можно добавить еще 2 страницы подключив там <a href="{{ mix('js/clicker.min.js') }}">скрипт</a>.
            </p>

            <hr class="featurette-divider my-5">

            <h2>Сайт: {{ $title }}</h2>

            @if ($show && !empty($chartJs))
                <div>
                    {!! $chartJs->render() !!}
                </div>

                <div class="mt-5">
                    <small>
                        <b>Примечание:</b><br>
                        Главная страница с адресом в виде '/' в легенде выглядит не корректно.<br>
                        Поэтому, для лучшего восприятия, она показывается вместе с именем сайта.
                    </small>
                </div>
            @else
            <p>Нет отслеживаемых страниц для показа</p>
            @endif

        </div>
    </div>
@endsection

<script src="{{ mix('js/chart.min.js') }}"></script>
