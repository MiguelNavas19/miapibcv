<?php

namespace App\Console\Commands;

use App\Models\ReferenceRecord;
use App\Services\UrlProviderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchExchangeRates extends Command
{

    protected $signature = 'rates:update';
    protected $description = 'Consulta el BCV y otros bancos para actualizar tasas';
    protected $urlProvider;
    protected array $banco = [];

    public function __construct(UrlProviderService $urlProvider)
    {
        parent::__construct();
        $this->urlProvider = $urlProvider;
        $this->banco = ['bdv', 'banplus', 'bnc', 'bcv'];
    }



    public function handle()
    {
        $this->info('Iniciando actualización de tasas...');
        $this->logApi();

        foreach ($this->banco as $banco) {
            try {
                $this->store($banco);
                $this->info("Banco {$banco} procesado.");
            } catch (\Exception $e) {
                // Si un banco falla, el log nos dirá por qué pero el cron seguirá con el siguiente
                $this->error("Error en {$banco}: " . $e->getMessage());
                Log::error("Fallo en cron para {$banco}: " . $e->getMessage());
            }
        }

        $this->info('Tasas actualizadas exitosamente.');
    }


    protected function store(string $banco)
    {
        $today = now()->toDateString();

        // 1. Verificamos si ya existe el registro para hoy y ese banco
        $exists = ReferenceRecord::where('source', $banco)
            ->where('date', $today)
            ->exists();

        if ($exists) {
            $this->info("El registro para {$banco} ya existe hoy. Saltando...");
            return;
        }

        // 2. Si no existe, buscamos el valor
        $value = $this->urlProvider->getStrategy($banco)->getValue();

        if ($value) {
            $record = new ReferenceRecord();
            $record->source = $banco;
            $record->value = $value;
            $record->date = $today;
            $record->save();

            $this->info("Guardado exitoso: {$banco} -> {$value}");
        } else {
            $this->warn("No se obtuvo valor para {$banco}");
        }
    }


    protected function logApi()
    {
        Log::info("SE EJECUTO EL CRON", [
            'time' => now()->toDateTimeString()
        ]);
    }
}
