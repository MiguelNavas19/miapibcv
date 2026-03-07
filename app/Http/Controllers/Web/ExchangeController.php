<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ReferenceRecord;
use Illuminate\Support\Facades\Cache;

class ExchangeController extends Controller
{
    public function index()
    {
        $rates = $this->getRates();

        return view('exchange.index', compact('rates'));
    }

    private function getRates(): ?array
    {
        $cacheKey = 'tasas_bancos_' . now()->toDateString();

        $records = Cache::remember($cacheKey, 3600, function () {
            return ReferenceRecord::where('date', now()->toDateString())
                ->get()
                ->keyBy('source');
        });

        if ($records->isEmpty()) {
            return null;
        }

        return $records->map(fn($item) => [
            'value' => $item->value,
            'date' => $item->date,
        ])->toArray();
    }
}
