<?php

namespace App\Services;

use App\Interfaces\UrlStrategy;
use App\Strategies\Url\UrlBanplus;
use App\Strategies\Url\UrlBcv;
use App\Strategies\Url\UrlBdv;
use App\Strategies\Url\UrlBnc;

class UrlProviderService
{
    public function getStrategy(string $identifier): UrlStrategy
    {
        return match ($identifier) {
            'banplus' => new UrlBanplus(new ScraperService()),
            'bnc' => new UrlBnc(new ScraperService()),
            'bcv' => new UrlBcv(new ScraperService()),
            'bdv' => new UrlBdv(),
            default  => new UrlBdv(),
        };
    }
}
