<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReferenceRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class ScraperController extends Controller
{

    public function show(Request $request)
    {
        $this->logApi($request);

        // Creamos una llave única para el caché basada en la fecha de hoy
        $cacheKey = 'tasas_bancos_' . now()->toDateString();

        // Intentamos obtener del caché, si no existe, ejecutamos la lógica y guardamos por 1 hora (3600 seg)
        $records = Cache::remember($cacheKey, 3600, function () {
            return ReferenceRecord::where('date', now()->toDateString())
                ->get()
                ->keyBy('source');
        });

        if ($records->isEmpty()) {
            return response()->json(['message' => 'Tasas no disponibles aún'], 404);
        }

        return response()->json([
            'message' => 'Consulta exitosa',
            ...$records->map(fn($item) => [
                'value' => $item->value,
                'date' => $item->date
            ])->all()
        ]);
    }


    public function getInfo(Request $request, $date = null, $source = null)
    {
        $this->logApi($request);

        // 1. Validar fecha (asumiendo que hoy es el default si es null)
        $queryDate = $date ?? now()->toDateString();
        if (!$this->validateDate($queryDate)) {
            return response()->json(['message' => 'Fecha inválida'], 400);
        }

        $cacheKey = "tasas_bancos_{$queryDate}";

        // 2. Consulta base
        $allRecords = Cache::remember($cacheKey, 3600, function () use ($queryDate) {
            return ReferenceRecord::whereDate('date', $queryDate)->get();
        });

        // 3. Filtro opcional por Banco
        $records = $source
            ? $allRecords->where('source', $source)
            : $allRecords;


        if ($records->isEmpty()) {
            return response()->json(['message' => 'No se encontraron datos'], 404);
        }

        // 4. Transformar la colección a un formato dinámico
        $formattedData = $records->mapWithKeys(function ($item) {
            return [
                $item->source => [
                    'value' => $item->value,
                    'date'  => $item->date
                ]
            ];
        });

        return response()->json([
            'message' => 'Consulta exitosa',
            ...$formattedData
        ], 200);
    }


    private function validateDate(&$date)
    {
        if (empty($date)) {
            $date = now()->toDateString();
            return true;
        }

        try {

            $dt = Carbon::parse($date);
            $date = $dt->toDateString();

            return $dt->lessThanOrEqualTo(now());
        } catch (\Exception $e) {
            return false;
        }
    }


    protected function logApi($request)
    {
        Log::info("API Access", [
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'time' => now()->toDateTimeString()
        ]);
    }
}
