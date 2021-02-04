'use strict';

( function() {
    // адрес обработчика данных
    const url = 'http://server.ru/some.php';

    // адрес текущей страницы
    const location = window.location.href;
    // ширина окна браузера
    const width = document.documentElement.clientWidth;

    // для сокращения данных храним метку времени со сдвигом на текущую минуту
    let timeoffset = getTimeOffset();

    // Массив для хранения кликов
    let clicks = [];

    /**
     * Обработчик кликов
     * @param evt
     */
    function onClickHandler(evt) {
        const data = getPageXY(evt);
        const now = new Date();
        data.t = now.getTime() - timeoffset;

        clicks.push(data);
    }

    /**
     *  Отправка данных по таймингу
     */
    function onTimeHandler() {
        // отправка данных
        if (sendData()) {
            // сброс данных, для избежания дублирования
            clicks = [];
            timeoffset = getTimeOffset();
        }
    }

    /**
     * Отправка данных
     * @returns {boolean}
     */
    function sendData() {
        let error = false;

        if(clicks.length) {

            const data = {
                l: location,
                w: width,
                o: timeoffset,
                c: clicks
            }
            console.log(data);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(() => { error = true;})
            .catch(() => {})
        }

        return error;
    }

    /**
     * Получение координат клика
     * @param e - cобытие
     * @returns {{x: number, y: number}}
     */
    function getPageXY(e) {
        let pageX, pageY;

        // поддержка старых браузеров, если нет pageX..
        if (e.pageX == null && e.clientX != null) {
            var html = document.documentElement;
            var body = document.body;

            pageX = e.clientX + (html.scrollLeft || body && body.scrollLeft || 0);
            pageX -= html.clientLeft || 0;

            pageY = e.clientY + (html.scrollTop || body && body.scrollTop || 0);
            pageY -= html.clientTop || 0;
        }
        else {
            pageX = e.pageX;
            pageY = e.pageY;
        }

        return {x: pageX, y: pageY }
    }

    /**
     * Возвращает метку времени на начало текущей минуты
     * @returns {number}
     */
    function getTimeOffset() {
        let now = new Date();
        let current = new Date(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours(), now.getMinutes(), 0);
        return current.getTime();
    }

    document.addEventListener('DOMContentLoaded', () => {
        // обработка кликов
        document.addEventListener('click', onClickHandler);
        // обработка покидания страницы
        window.onbeforeunload = function() {
            sendData();
        }

        setInterval(onTimeHandler, 5000);
    });
})();
