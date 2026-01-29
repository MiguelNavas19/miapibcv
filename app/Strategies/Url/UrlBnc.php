<?php

namespace App\Strategies\Url;

use App\Interfaces\UrlStrategy;

class UrlBnc implements UrlStrategy
{


    protected $scraper;

    public function __construct($scraper)
    {
        $this->scraper = $scraper;
    }


    public function getValue(): float
    {
        $url = 'https://www.bncenlinea.com/';
        $value = $this->scraper->scrapeData($url, 'bnc');
        return $value;
    }
}
