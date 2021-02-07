'use strict';

(function () {

    /**
     * Загрузка, управление и отрисовка тепловой карты
     */
    window.requestAnimationFrame = window.requestAnimationFrame
        || window.mozRequestAnimationFrame
        || window.webkitRequestAnimationFrame
        || window.msRequestAnimationFrame;

    let radius = document.querySelector('#radius');
    let blur = document.querySelector('#blur');
    let changeType = 'oninput' in radius ? 'oninput' : 'onchange';

    radius[changeType] = blur[changeType] = function (e) {
        heat.radius(+radius.value, +blur.value);
        frame = frame || window.requestAnimationFrame(draw);
    };

    let heat = simpleheat('canvas').data(data).max(18), frame;

    function draw() {
        heat.draw();
        frame = null;
    }

    draw();

    // Отрисовка движения мыши на тепловой карте
    /*
    document.querySelector('#canvas').onmousemove = function (e) {
        heat.add([e.layerX, e.layerY, 1]);
        frame = frame || window.requestAnimationFrame(draw);
    };
    */

    /**
     * Переключение размеров ширины страницы
     */

    let width = document.querySelector('input[name=width-group]:checked');
    let canvas = document.querySelector('#canvas');
    let divIframe = document.querySelector('.iframe-wrap .iframe');
    let iframe = document.querySelector('.iframe-wrap .iframe');

    changeMap(width);

    document.querySelectorAll('input[name=width-group]').forEach(function (elem) {
        elem.onchange = function (evt) {
            width = evt.target.value;
            changeMap(width, evt.target.id);
        };
    });

    /**
     * Переключение карты кликов на другой размер ширины страницы
     * с перегрузкой данных по выбранному варианту
     * @param newWidth
     * @param id
     */
    function changeMap(newWidth, id = 0) {
        // меняем ширину экрана ( карта отображается в половинный размер)
        let width = Math.floor(parseInt(newWidth) / 2);
        if (width) {
            canvas.width = width;
            divIframe.style.width = width + "px";
            iframe.width = width;
        }

        // перегружаем в simpleheat данные для выбранной ширины экрана
        if (heat && id && heatData[id].length) {
            heat.data(heatData[id]);
            draw();
        }
    }
})();
