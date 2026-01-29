<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReferenceRecord;
use App\Services\UrlProviderService;

class ScraperController extends Controller
{
    protected $urlProvider;
    protected array $banco = array();

    public function __construct(UrlProviderService $urlProvider)
    {
        $this->urlProvider = $urlProvider;
        $this->banco = ['bdv', 'banplus', 'bnc'];
    }


    public function show()
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
        } else {

            foreach ($this->banco as $banco) {
                $this->store($banco);
            }
            return $this->getData();
        }
    }

    public function store(string $banco)
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
                ] : null
            ], 200);
        }

        return response()->json(['message' => 'No se encontraron datos'], 404);
    }

    public function getInfo($date = null, $source = null)
    {
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
        if (!$date) {
            $date = now()->toDateString();
            return true;
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
            $dt = \DateTime::createFromFormat('d-m-Y', $date);
            if ($dt) {
                $date = $dt->format('Y-m-d');
            } else {
                return false;
            }
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }

        return $date <= now()->toDateString();
    }
}
