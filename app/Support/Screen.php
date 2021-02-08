<?php

namespace App\Support;

use Screen\Capture;
use Screen\Exceptions\InvalidUrlException;
use Screen\Exceptions\PhantomJsException;

class Screen
{
    // раздел для сохранения снимков сайта
    private const IMAGES_DIR = 'img/sites/';

    // адрес сайта
    private $url;
    // ширина создаваемого снимка
    private $width = 1024;
    // высота создаваемого снимка
    private $height = 768;
    // формат записи
    private $type = 'png';
    // флаг необходимости перезаписи снимка, если он существует
    private $overwrite = false;

    /**
     * Screen constructor.
     * @param string $url
     * @throws InvalidUrlException
     */
    public function __construct(string $url)
    {
        if (empty(trim($url))) {
            throw new InvalidUrlException($url);
        }

        $this->url = $url;
    }

    /**
     * Установка параметров снимка
     * @param int    $width     - ширина
     * @param int    $height    - высота
     * @param string $type      - формат
     * @param bool   $overwrite - флаг необходимости перезаписи снимка, если он существует
     */
    public function setParams(int $width = 1024, int $height = 768, string $type = 'png', bool $overwrite = false): void
    {
        if ($width) {
            $this->width = $width;
        }

        if ($height) {
            $this->height = $height;
        }

        if (!empty($type)) {
            $this->type = $type;
        }

        $this->overwrite = $overwrite;
    }

    /**
     * Создает снимок указанного сайта
     * @return string - путь к снимку
     */
    public function getScreen(): string
    {
        $path = '';

        $filename = $this->getFileName();

        if (!$this->overwrite && file_exists($filename) && filesize($filename)) {
            $path = $filename;
        } else {
            try {
                $this->mkdirRecursive(self::IMAGES_DIR);
            } catch (\RuntimeException $e) {
                error_log($e->getMessage(), 0);
            }

            try {
                $this->makeScreen($filename);
            } catch (PhantomJsException $e) {
                error_log($e->getMessage(), 0);
            }
        }

        return $path;
    }

    /**
     * Создание снимка сайта
     * доступные форматы 'jpg' и 'png', по умолчанию 'jpg'.
     * @param $filename - имя сохраняемого файла
     * @throws PhantomJsException
     */
    private function makeScreen($filename): void
    {
        $screen = new Capture($this->url);
        $screen->setWidth($this->width)
            ->setHeight($this->height)
            ->setImageType($this->type)
            ->save($filename);
    }

    /**
     * Рекурсивное создание разделов
     * @param string $path
     */
    private function mkdirRecursive(string $path): void
    {
        if (!is_dir($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
    }

    /**
     * Создание имени файла по установленным параметрам
     * @return string
     */
    private function getFileName(): string
    {
        return self::IMAGES_DIR
            . md5("{$this->url}-{$this->width}-{$this->width}")
            . $this->type;
    }
}
