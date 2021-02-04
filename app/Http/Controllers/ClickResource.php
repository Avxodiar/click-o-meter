<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClickResource extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request = $request->all();

        $link = $request['l'];

        // проверка обязательных параметров - ссылки и данных по кликам
        if (!empty($link) && count($request['c'])) {
            $width = $request['w'];
            $timeOffset = $request['o'];

            $data = [];
            foreach ($request['c'] as $click) {
                if (!empty($click)) {
                    $data[] = [
                        'time' => $timeOffset + $click['t'],
                        'x' => $click['x'],
                        'y' => $click['y']
                    ];
                }
            }

            // если все данные в наличии, то сохраняем в БД
            if(count($data)) {
                $url = parse_url($link);

                // данные для сохранения
                $query1 = $url['host'];
                $query2 = [$url['path'], $width];
                $query3 = $data;

            }
        }
    }
}
