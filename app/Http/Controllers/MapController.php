<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Http\Request;

class MapController extends ClickController
{
    // количество отображаемых ссылок
    protected const MAX_LINK = 20;

    private $maxHeight = 0;

    // ВАЖНО! Изменение этих параметров потребует изменений
    // в шаблоне map-heat и скрипте-отрисовщике карты кликов js/link-map
    private const SIMPLEHEAT_PROPORTION = 0.5;
    private const SIMPLEHEAT_HALF_SPOT_SIZE = 60;

    /**
     * Вывод списка ссылок для вывода карты кликов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $links = $this->site->links()->take(self::MAX_LINK)->get();

        $linkList = [];
        if (count($links)) {
            $linkList = $links->toArray();
        }

        return view('map')
            ->with('siteName', $this->getSiteName())
            ->with('links', $linkList);
    }

    /**
     * Карта кликов для указанной ссылки
     * @param int $id - id ссылки в БД
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLink(int $id)
    {
        $link = Link::findOrFail($id);
        if ($link) {
            $clicks = $link->clicks()->select('width', 'x', 'y')->get();

            if ($clicks) {
                // группируем клики по ширине странице, по позициям и учитываем совпадения
                $groupedClicks = $this->getGroupedClicks($clicks);

                // данные для js
                $jsData = $this->getJSData($groupedClicks);

                // размеры страниц
                $widthLinks = array_keys($groupedClicks);

                // добавляем переменная для загрузки данных в simpleheat по первой ссылке
                $jsData .= 'let data = heatData[' . $widthLinks[0] . '];';

                return view('map-heat', [
                    'url' => $this->getSiteName() . $link->link,
                    'widthLinks' => $widthLinks,
                    'width' => $widthLinks[0],
                    'maxY' => $this->maxHeight,
                    'jsData' => $jsData,
                ]);
            }
        }

        return view('map-empty')
            ->with('url', $this->getSiteName() . $link->link);
    }

    /**
     * Группируем позиции кликов по ширине странице, по позициям и с учетом совпадений
     * и устанавливаем макс.высоту страницы
     * координаты кликов уменьшаются в 2 раза т.к. карта выводится уменьшенная в 2 раза (SIMPLEHEAT_PROPORTION)
     * @param $clicks
     * @return array
     */
    private function getGroupedClicks($clicks): array
    {
        $groupClicks = [];
        // макс.значение позиции клика по вертикали
        $maxY = 0;
        foreach ($clicks as $click) {
            $width = $click['width'];
            $x = floor($click['x'] * self::SIMPLEHEAT_PROPORTION);
            $y = floor($click['y'] * self::SIMPLEHEAT_PROPORTION);

            $count = $groupClicks[$width][$x][$y] ?? 0;
            $groupClicks[$width][$x][$y] = ++$count;

            $maxY = $y > $maxY ? $y : $maxY;
        }
        // увеличиваем высоту на половину максимального разметра теплового пятна
        // чтобы не было его усечения по нижнему краю карты
        $this->maxHeight = $maxY + self::SIMPLEHEAT_HALF_SPOT_SIZE;

        // ВАЖНО! сортируем по убыванию, т.к. в библиотеке simpleheat
        // при увеличеннии размеров отрисовываемой области возникают артефакты
        // т.е. первоначальный размер должен быть самым большим!
        krsort($groupClicks);

        return $groupClicks;
    }

    /**
     * Формируем данные кликов для передачи в js библиотеку simpleheat
     * @param array $groupClicks
     * @return string
     */
    private function getJSData(array $groupClicks): string
    {
        $jsData = 'let heatData = {';

        foreach ($groupClicks as $width => $coords) {
            $jsData .= "$width: [";
            foreach ($coords as $x => $data) {
                foreach ($data as $y => $count) {
                    $jsData .= "[{$x},{$y},{$count}],";
                }
            }
            $jsData = mb_substr($jsData, 0, -1) . '],';
        }
        $jsData = mb_substr($jsData, 0, -1) . '};';

        return $jsData;
    }
}
