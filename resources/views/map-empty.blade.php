@extends('layout')

@section('content')
    <div class="container">
        <div class="m-5">
            <h1>Карта кликов: {{ $url }}</h1>

            <p>
                Клики выводятся без сортировки по времени, но с разделением по ширине экрана,<br>
                т.к. это напрямую влияет на позиционирование точек клика на странице.
            </p>

            <div class="alert alert-warning" role="alert">
                К сожалению, нет данных по этой ссылке.<br>
                Показ карты кликов не доступен.
            </div>

        </div>
    </div>

@endsection


