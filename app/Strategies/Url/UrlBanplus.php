<?php

namespace App\Strategies\Url;

use App\Interfaces\UrlStrategy;

class UrlBanplus implements UrlStrategy
{
    protected $scraper;

    public function __construct($scraper)
    {
        $this->scraper = $scraper;
    }


    public function getValue(): float
    {
        $url = 'https://www.banplus.com/';
        $value = $this->scraper->scrapeData($url, 'banplus');
        return $value;
    }
}
