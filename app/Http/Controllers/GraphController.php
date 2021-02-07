<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GraphController extends ClickController
{
    // период отображения за 1 последний день
    private const TIME_PERIOD = 86400;
    // Кол-во точек на графике по оси Х ( 1 точка на час)
    private const PRECESSION = 24;

    // цвета отображения ссылок (не константа, т.к. требуется работа со сдвигом указателя)
    private $COLOR_LINKS = [
        'green' => "rgb(75, 192, 192)",
        'purple' => "rgb(153, 102, 255)",
        'orange' => "rgb(255, 159, 64)",
        'red' => "rgb(255, 99, 132)",
        'blue' => "rgb(54, 162, 235)",
    ];

    /**
     * Отображение для текущего выбранного сайта графика кликов за последние сутки
     * по MAX_LINK первым ссылкам в Link (таблица links)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        // получаем список ссылок
        $links = $this->site->links()->take(self::MAX_LINK)->get();

        $showGraph = false;
        $chartJs = [];

        if (count($links)) {
            // формируем данные для графика
            $dataSets = $this->getDataSet($links);

            // если в БД есть данные по кликам
            if (!empty($dataSets)) {
                $showGraph = true;

                // создание и настройка отображения линейного графика
                $chartJs = app()->chartjs
                    ->name('lineChart')
                    ->type('line')
                    ->labels($this->getLabels())
                    ->datasets($dataSets)
                    ->optionsRaw($this->getChartJsOption());
            }
        }

        return view('graph')
            ->with('title', $this->getSiteName())
            ->with('show', $showGraph)
            ->with('chartJs', $chartJs);
    }

    /**
     * Формирование данных для графика
     * @param $links
     * @return array
     */
    private function getDataSet($links): array
    {
        $dataSets = [];
        foreach ($links as $link) {
            // подготовленные данные по кликам за TIME_PERIOD времени
            $clickList = $this->getCliks($link);

            if (count($clickList)) {
                // каждая ссылка отображается своим цветом
                $color = $this->getNextColor();

                // Главная страница с адресом '/' в легенде на графике отображается не корректно.<br>
                // Поэтому для лучшего восприятия отображается вместе с именем сайта.
                $linkName = ($link->link === '/') ? $this->getSiteName() . $link->link : $link->link;

                $dataSets[] = [
                    'label' => $linkName,
                    'fill' => 'false',
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'data' => $clickList,
                ];
            }
        }

        return $dataSets;
    }

    /**
     * Формирование массива по времени с кол-вом кликов для указанной ссылки
     * @param $link
     * @return array
     */
    private function getCliks($link): array
    {
        $res = $link->clicks()->selectRaw('UNIX_TIMESTAMP(time) AS times')->whereRaw('time > NOW() - INTERVAL 1 DAY');
        $clicks = $res->get();

        // период времени по которым группируем клики
        $period = self::TIME_PERIOD / self::PRECESSION;

        $lastDay = time() - self::TIME_PERIOD;

        $clickList = array_fill(0, self::PRECESSION, 0);
        foreach ($clicks as $click) {
            $point = (int) (($click->times - $lastDay) / $period);
            $clickList[$point]++;
        }

        return $clickList;
    }

    /**
     * Генерирция подписей по оси X (часы) в формате HH:MM
     * @return array
     */
    private function getLabels(): array
    {
        $currHour = (int) date('H');

        $labels = [];
        $step = 24 / self::PRECESSION;

        for ($i = 0; $i < self::PRECESSION; $i++) {
            $time = $i * $step;

            $hour = floor($time);
            $minute = round(($time - $hour) * 60);

            $newHour = ($currHour + $hour) % 24;

            $labels[] = date("H-i", mktime($newHour, $minute));
        }

        return $labels;
    }

    /**
     * Возвращает следующий цвет из списка $COLOR_LINKS
     * @return string
     */
    private function getNextColor(): string
    {
        $colors = &$this->COLOR_LINKS;

        $color = current($colors);
        next($colors);
        if (is_null(key($colors))) {
            reset($colors);
        }

        return $color;
    }

    /**
     * Настройка библиотеки ChartJs
     * @return string
     */
    private function getChartJsOption(): string
    {
        return "{
                    title: {
                        display: true,
                        text: 'График распределения кликов по времени за последние сутки'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'Время, ч'
                            }
                        }],
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'Кол-во кликов'
                            }
                        }]
                    }
                }";
    }
}
