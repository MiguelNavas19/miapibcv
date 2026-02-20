<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReferenceRecord;
use App\Services\UrlProviderService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    protected $urlProvider;
    protected array $banco = array();

    public function __construct(UrlProviderService $urlProvider)
    {
        $this->urlProvider = $urlProvider;
        $this->banco = ['bdv', 'banplus', 'bnc', 'bcv'];
    }


    public function show(Request $request)
    {
        $this->logApi($request);
        $today = now()->toDateString();

        $records = ReferenceRecord::where('date', $today)
            ->get()
            ->keyBy('source');

        if ($records->isNotEmpty()) {
            $data = $records->map(fn($item) => [
                'value' => $item->value,
                'date'  => $item->date
            ]);

            return response()->json([
                'message' => 'Consulta exitosa',
                ...$data->toArray()
            ], 200);
        }

        foreach ($this->banco as $banco) {
            $this->store($banco);
        }

        return $this->getData();
    }

    protected function store(string $banco)
    {
        $value = $this->urlProvider->getStrategy($banco)->getValue();
        if ($value) {
            $record = new ReferenceRecord();
            $record->source = $banco;
            $record->value = $value;
            $record->date = now()->toDateString();
            $record->save();
        }

        return response()->json(['error' => 'No se pudo obtener el dato'], 500);
    }



    private function getData()
    {
        $record = ReferenceRecord::where('date', now()->toDateString())->get();

        if ($record->isNotEmpty()) {
            $bdvRecord = $record->firstWhere('source', 'bdv');
            $bncRecord = $record->firstWhere('source', 'bnc');
            $banplusRecord = $record->firstWhere('source', 'banplus');
            $bcvRecord = $record->firstWhere('source', 'bcv');
            return response()->json([
                'message' => 'Consulta exitosa',
                'bdv' => $bdvRecord ? [
                    'value' => $bdvRecord->value,
                    'date' => $bdvRecord->date
                ] : null,
                'bnc' => $bncRecord ? [
                    'value' => $bncRecord->value,
                    'date' => $bncRecord->date
                ] : null,
                'banplus' => $banplusRecord ? [
                    'value' => $banplusRecord->value,
                    'date' => $banplusRecord->date
                ] : null,
                'bcv' => $bcvRecord ? [
                    'value' => $bcvRecord->value,
                    'date' => $bcvRecord->date
                ] : null
            ], 200);
        }

        return response()->json(['message' => 'No se encontraron datos'], 404);
    }

    public function getInfo(Request $request, $date = null, $source = null)
    {

        $this->logApi($request);

        if (!$this->validateDate($date)) {
            return response()->json(['message' => 'Fecha inválida'], 400);
        }

        $record = ReferenceRecord::wheredate('date', $date ?? now()->toDateString());

        if ($source) {
            $record->where('source', $source);
        }

        $record = $record->get();

        if ($record->isNotEmpty()) {
            if ($source) {
                $bankRecord = $record->first();
                return response()->json([
                    'message' => 'Consulta exitosa',
                    $source => $bankRecord ? [
                        'value' => $bankRecord->value,
                        'date' => $bankRecord->date
                    ] : null
                ], 200);
            } else {
                $bdvRecord = $record->firstWhere('source', 'bdv');
                $bncRecord = $record->firstWhere('source', 'bnc');
                $banplusRecord = $record->firstWhere('source', 'banplus');
                $bcvRecord = $record->firstWhere('source', 'bcv');
                return response()->json([
                    'message' => 'Consulta exitosa',
                    'bdv' => $bdvRecord ? [
                        'value' => $bdvRecord->value,
                        'date' => $bdvRecord->date
                    ] : null,
                    'bnc' => $bncRecord ? [
                        'value' => $bncRecord->value,
                        'date' => $bncRecord->date
                    ] : null,
                    'banplus' => $banplusRecord ? [
                        'value' => $banplusRecord->value,
                        'date' => $banplusRecord->date
                    ] : null,
                    'bcv' => $bcvRecord ? [
                        'value' => $bcvRecord->value,
                        'date' => $bcvRecord->date
                    ] : null
                ], 200);
            }
        } else {
            return response()->json(['message' => 'No se encontraron datos'], 404);
        }
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
