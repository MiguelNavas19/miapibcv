<?php

namespace App\Console\Commands;

use App\Models\ReferenceRecord;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchExchangeRates extends Command
{

    protected $signature = 'rates:update';
    protected $description = 'Consulta el BCV y otros bancos para actualizar tasas';
    protected array $banco = [];

    public function __construct()
    {
        parent::__construct();
        // lista de fuentes a consultar
        $this->banco = ['bdv', 'banplus', 'bnc', 'bcv'];
    }



    public function handle()
    {
        $this->info('Iniciando actualización de tasas...');
        $this->logApi();

        foreach ($this->banco as $banco) {
            try {
                // despachar job para procesar en cola, permitir escalado horizontal
                \App\Jobs\UpdateExchangeRate::dispatch($banco);
                $this->info("Job encolado para {$banco}.");
            } catch (\Exception $e) {
                $this->error("No se pudo encolar {$banco}: " . $e->getMessage());
                Log::error("Fallo encolar job para {$banco}: " . $e->getMessage());
            }
        }

        $this->info('Todos los trabajos han sido disparados.');
    }


    // la lógica de almacenamiento se ha movido al job UpdateExchangeRate


    protected function logApi()
    {
        Log::info("SE EJECUTO EL CRON", [
            'time' => now()->toDateTimeString()
        ]);
    }
}
