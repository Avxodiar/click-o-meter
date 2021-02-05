<?php

namespace App\Http\Controllers;

use App\Click;
use App\Link;
use App\Site;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClickResource extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request = $request->all();

        $link = $request['l'];
        $clicks = $request['c'];

        // проверка обязательных параметров - ссылки и данных по кликам
        if (!empty($link) && count($clicks)) {
            $width = $request['w'];
            $timeOffset = $request['o'];

            $dataClicks = [];
            foreach ($clicks as $click) {
                if (
                    !empty($click['t'])
                    && !empty($click['x'])
                    && !empty($click['y'])
                ) {
                    $timestamp = (int) ($timeOffset + $click['t']) / 1000;
                    $dataClicks[] = [
                        'time' => date("Y-m-d H:i:s", $timestamp),
                        'width' => $width,
                        'x' => $click['x'],
                        'y' => $click['y'],
                    ];
                }
            }

            // если все данные в наличии, то сохраняем в БД
            if (count($dataClicks)) {
                $url = parse_url($link);

                // сохраняем имя сайта
                $site = Site::firstOrCreate([ 'name' => $url['host'] ]);

                // сохраняем ссылку(URN)
                $link = Link::firstOrNew([ 'link' => $url['path'] ]);
                if(!$link->exists) {
                    $site->links()->save($link);
                }

                // сохраняем клики
                $clickList = [];
                foreach ($dataClicks as $dataClick) {
                    $clickList[] = (new Click)->fill($dataClick);
                }
                $link->clicks()->saveMany($clickList);
            }
        }
    }
}
