<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;

abstract class ClickController extends Controller
{
    // Для упрощения показывается 1-ый сайт в БД
    private const SITE_ID = 1;

    // количество отображаемых ссылок
    protected const MAX_LINK = 5;

    // Выбранный сайт
    protected $site;

    public function __construct()
    {
        $this->setSite();
    }

    abstract public function show();

    /**
     * Задание сайта
     * @param int $siteId
     */
    protected function setSite(int $siteId = self::SITE_ID): void
    {
        $this->site = Site::findOrFail($siteId);
    }

    /**
     * Имя сайта
     * @return string
     */
    protected function getSiteName(): string
    {
        return $this->site->name ?? '';
    }
}
