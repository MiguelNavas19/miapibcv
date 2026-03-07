<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ScraperService
{
    public function scrapeData(string $url, string $banco)
    {
        /** @var Response $response */
        $response = Http::retry(3, 100)
            ->withOptions(['verify' => false])
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                'Referer' => $url,
            ])
            ->get($url);

        if (!$response->successful()) {
            return null; // elimina HTTP 4xx/5xx
        }

        $crawler = new Crawler($response->body());

        return match ($banco) {
            'banplus' => $this->parseBanplusData($crawler),
            'bnc' => $this->parseBNCData($crawler),
            'bcv' => $this->parseBCVData($crawler),
            default  => 0.00,
        };
    }

    private function parseBanplusData($crawler)
    {
        $element = $crawler->filter('.awb-news-ticker-link');
        if ($element->count() === 0) {
            throw new \Exception('No se encontró el elemento esperado en Banplus');
        }

        $text = $element->text();

        $valor = null;
        if (preg_match('/tasa de cambio\s+(.*)/', $text, $matches)) {
            if (preg_match_all('/[0-9]+,[0-9]+/', $matches[1], $coincidencias) && !empty($coincidencias[0])) {
                $valor = trim($coincidencias[0][0]);
            }
        }

        if ($valor === null) {
            $valor = $text;
        }

        return $this->cleanValue($valor);
    }

    private function parseBNCData($crawler)
    {
        $items = $crawler->filter('.ItemSpace')->each(function (Crawler $node) {
            $text = $node->text();
            return str_contains($text, 'USD $ Compra Bs:') ? $text : null;
        });

        $filteredItems = array_values(array_filter($items));
        if (empty($filteredItems)) {
            throw new \Exception('No se encontró el texto esperado en BNC');
        }

        preg_match_all('/[0-9]+,[0-9]+/', $filteredItems[0], $matches);
        if (empty($matches[0])) {
            throw new \Exception('No se encontró el valor numérico en BNC');
        }

        $value = $matches[0][0];
        return $this->cleanValue($value);
    }



    private function parseBCVData($crawler)
    {

        $element = $crawler->filter('#dolar');
        if ($element->count() === 0) {
            throw new \Exception('No se encontró el elemento esperado en Banplus');
        }

        $text = $element->text();

        if (preg_match('/USD\s+(.*)/', $text, $matches)) {
            if (preg_match_all('/[0-9]+,[0-9]+/', $matches[1], $coincidencias) && !empty($coincidencias[0])) {
                $valor = trim($coincidencias[0][0]);
            }
        }

        if ($valor === null) {
            $valor = $text;
        }

        return $this->cleanValue($valor);
    }


    private function cleanValue($value)
    {
        return (float) preg_replace('/[^0-9.]/', '', str_replace(',', '.', $value));
    }
}
