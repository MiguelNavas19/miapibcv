<?php

namespace App\Strategies\Url;

use App\Interfaces\UrlStrategy;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class UrlBdv implements UrlStrategy
{
    protected string $url = 'https://www.bancodevenezuela.com/files/tasas/tasas2.json';

    public function getValue(): float
    {
        try {
            /** @var Response $response */
            $response = Http::retry(3, 100)
                ->timeout(10)
                ->withOptions(['verify' => true])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36',
                    'Accept'     => 'application/json',
                ])
                ->get($this->url);

            if ($response->failed()) {
                throw new Exception("Error de conexión con BDV: " . $response->status());
            }

            $data = $response->json();

            $rawRate = data_get($data, 'menudeo.compra.dolares');

            if (!$rawRate) {
                throw new Exception("Estructura de JSON no reconocida o tasa faltante.");
            }

            return $this->formatRate($rawRate);
        } catch (Exception $e) {
            Log::error("Fallo obteniendo tasa BDV: " . $e->getMessage());
            return "0.00";
        }
    }


    private function formatRate(string $rate): float
    {

        $cleanRate = str_replace(',', '.', $rate);
        return (float) preg_replace('/[^0-9.]/', '', $cleanRate);
    }
}
