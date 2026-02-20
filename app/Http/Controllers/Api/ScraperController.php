<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReferenceRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ScraperController extends Controller
{


    public function show(Request $request)
    {

        $this->logApi($request);
        $records = ReferenceRecord::where('date', now()->toDateString())
            ->get()
            ->keyBy('source');

        if ($records->isEmpty()) {
            return response()->json(['message' => 'Tasas no disponibles aún'], 404);
        }

        return response()->json([
            'message' => 'Consulta exitosa',
            ...$records->map(fn($item) => ['value' => $item->value, 'date' => $item->date])
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

        // 2. Consulta base
        $query = ReferenceRecord::whereDate('date', $queryDate);

        // 3. Filtro opcional por fuente
        if ($source) {
            $query->where('source', $source);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            return response()->json(['message' => 'No se encontraron datos'], 404);
        }

        // 4. Transformar la colección a un formato dinámico
        // Esto agrupa los datos por 'source' automáticamente
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
            ...$formattedData // Inyecta dinámicamente 'bcv', 'bdv', etc.
        ], 200);
    }




    private function validateDate(&$date)
    {
        if (empty($date)) {
            $date = now()->toDateString();
            return true;
        }

        try {

            $dt = \Illuminate\Support\Carbon::parse($date);

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
