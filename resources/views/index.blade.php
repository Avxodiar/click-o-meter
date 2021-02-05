@extends('layout')

@section('content')
    <div class="container">
        <div class="m-5">
            <h1>Тестовое задание PHP</h1>

            <p class="lead">
                Задание заключается в следующем: нужно создать свою простую систему веб-аналитики с картами кликов. Задание спроектировано так, чтобы можно было показать как фронтенд- и бэкенд-навыки, так и работу с базами данных и знание алгоритмов.</p>

            <h2>Задача</h2>
            <p>Мы хотим видеть, где на сайте пользователи чаще всего кликают. И в какое время суток пользователи более активны.</p>

            Решение соискателя должно содержать два компонента.</p>

            <h2>Первый компонент</h2>
            <p>JS-код, который внедряется на любой сторонний сайт. Этот скрипт отслеживает клики пользователей: время и координаты.</p>

            <h2>Второй компонент</h2>
            <p>Сайт-админка, на котором можно увидеть информацию для каждого мониторируемого сайта: карта кликов (подобно Яндекс метрике) и график распределения активности пользователя по времени суток.</p>

            <ul>
                <li>
                    <a href="/test1">Пример сайта с имплементированным скриптом</a>
                </li>
                <li>
                    <a href="/map">Просмотр карты кликов в админке</a>
                </li>
            </ul>

            <h2>Взаимосвязь</h2>
            <p>Скрипт встроенный на стороннем сайте отправляет данные на серверную часть проекта по API. Принимающая сторона агрегирует данные в базу данных.</p>

            <p><a href="/map">График распределения кликов по времени суток</a></p>

            <p>Для реализации нужно использовать PHP + Laravel для API, а для фронтенда всё, что посчитаете нужным.</p>

            <h3>Примечание</h3>
            <p>Оформите проект как посчитаете нужным, приложите инструкцию по запуску и тестированию. В репозитории может быть готовая или промежуточная реализация тестового задания.</p>

        </div>
    </div>
@endsection
