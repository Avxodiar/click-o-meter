<?php

namespace App\Http\Controllers;

use App\Link;
use App\Site;
use Illuminate\Http\Request;

class MapController extends Controller
{
    // Для упрощения показывается 1-ый сайт в БД
    private const SITE_ID = 1;
    // количество отображаемых ссылок
    private const MAX_LINK = 20;

    // ВАЖНО! Изменение этих параметров потребует изменений в
    //  шаблоне map-heat и скрипте-отрисовщике карты кликов js/link-map
    private const SIMPLEHEAT_PROPORTION = 0.5;
    private const SIMPLEHEAT_HALF_SPOT_SIZE = 60;

    /**
     * Вывод списка ссылок для вывода карты кликов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $site = Site::find(self::SITE_ID);

        $links = $site->links()->take(self::MAX_LINK)->get();

        $linkList = [];
        if (count($links)) {
            $linkList = $links->toArray();
        }

        return view('map')
                ->with('siteName', $site->name)
                ->with('links', $linkList);
    }

    /**
     * Карта кликов для указанной ссылки
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLink(int $id)
    {
        $site = Site::find(self::SITE_ID);

        $link = Link::find($id);
        if($link){
            $clicks = $this->getCliks($link);

            // Группируем позиции кликов
            // координаты уменьшаем в 2 раза т.к. карта выводится уменьшенная в 2 раза (SIMPLEHEAT_PROPORTION)
            $widthGroups = [];
            // макс.значение позиции клика по вертикали
            $maxY = 0;
            foreach ($clicks as $click) {
                $width = $click['width'];
                $x = floor($click['x'] * self::SIMPLEHEAT_PROPORTION);
                $y = floor($click['y'] * self::SIMPLEHEAT_PROPORTION);

                $count = $widthGroups[$width][$x][$y] ?? 0;
                $widthGroups[$width][$x][$y] = ++$count;

                $maxY = $y > $maxY ? $y : $maxY;
            }
            // увеличиваем высоту на половину максимального разметра теплового пятна
            // чтобы не было его усечения по нижнему краю карты
            $maxY += self::SIMPLEHEAT_HALF_SPOT_SIZE;

            // ВАЖНО! сортируем по убыванию, т.к. в библиотеке simpleheat
            // при увеличеннии размеров отрисовываемой области возникают артефакты
            // т.е. первоначальный размер должен быть самым большим!
            krsort($widthGroups);

            // Формируем данные кликов для js библиотеки simpleheat
            $jsData = 'let heatData = {';
            foreach ($widthGroups as $width => $coords) {
                $jsData .= "$width: [";
                foreach ($coords as $x => $data) {
                    foreach ($data as $y => $count) {
                        $jsData .= "[{$x},{$y},{$count}],";
                    }
                }
                $jsData = mb_substr($jsData, 0, -1) . '],';
            }
            $jsData = mb_substr($jsData, 0, -1) . '};';


            $widthLinks = array_keys($widthGroups);
            // Переменная для загрузки данных в simpleheat по первой ссылке
            $jsData .= 'let data = heatData[' . $widthLinks[0] . '];';
        }

        return view('map-heat')
            ->with('url', $site->name . $link->link)
            ->with('widthGroups', $widthLinks)
            ->with('width', $widthLinks[0])
            ->with('maxY', $maxY)
            ->with('jsData', $jsData);
    }

    /**
     * Формирование массива по времени с кол-вом кликов для указанной ссылки
     * @param $link
     * @return mixed
     */
    private function getCliks($link)
    {
        $res = $link->clicks()->select('width', 'x', 'y');//->whereRaw('time > NOW() - INTERVAL 1 DAY');
        return $res->get();
    }
}
