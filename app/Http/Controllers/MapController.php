<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;

class MapController extends Controller
{
    // Для упрощения показывается 1-ый сайт в БД
    private const SITE_ID = 1;
    // количество отображаемых ссылок
    private const MAX_LINK = 20;

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
}
