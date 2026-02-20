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
    protected array $banco = array();

    public function __construct(UrlProviderService $urlProvider)
    {
        $this->urlProvider = $urlProvider;
        $this->banco = ['bdv', 'banplus', 'bnc', 'bcv'];
    }



    public function handle()
    {
        $this->info('Iniciando actualización de tasas...');


        foreach ($this->banco as $banco) {
            $this->store($banco);
        }


        $this->info('Tasas actualizadas exitosamente.');
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



    protected function logApi()
    {
        Log::info("SE EJECUTO EL CRON", [
            'time' => now()->toDateTimeString()
        ]);
    }
}
