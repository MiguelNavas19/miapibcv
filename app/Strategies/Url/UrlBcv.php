<?php

namespace App\Strategies\Url;

use App\Interfaces\UrlStrategy;

class UrlBcv implements UrlStrategy
{

    protected $scraper;

    public function __construct($scraper)
    {
        $this->scraper = $scraper;
    }


    public function getValue(): float
    {
        $url = 'https://www.bcv.org.ve/';
        $value = $this->scraper->scrapeData($url, 'bcv');
        return $value;
    }
}
