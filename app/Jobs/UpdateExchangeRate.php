<?php

namespace App\Jobs;

use App\Models\ReferenceRecord;
use App\Services\UrlProviderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateExchangeRate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $banco;

    public function __construct(string $banco)
    {
        $this->banco = $banco;
    }

    public function handle(UrlProviderService $urlProvider)
    {
        $today = now()->toDateString();

        // Si existe, usaremos el registro y lo actualizaremos según la lógica previa.
        $record = ReferenceRecord::where('source', $this->banco)
            ->where('date', $today)
            ->first();

        $newValue = $urlProvider->getStrategy($this->banco)->getValue();

        if (!$newValue) {
            Log::warning("No se obtuvo valor para {$this->banco}");
            return;
        }

        if ($record) {
            if ($record->value != $newValue && $this->banco != 'bcv') {
                $oldValue = $record->value;
                $record->update(['value' => $newValue]);
                Log::info("Actualizado: {$this->banco} cambió de {$oldValue} a {$newValue}");
            }
        } else {
            ReferenceRecord::create([
                'source' => $this->banco,
                'value'  => $newValue,
                'date'   => $today,
            ]);
            Log::info("Nuevo registro guardado: {$this->banco} -> {$newValue}");
        }
    }
}
