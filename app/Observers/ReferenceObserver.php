<?php

namespace App\Observers;

use App\Models\ReferenceRecord;
use Illuminate\Support\Facades\Cache;

class ReferenceObserver
{
    /**
     * Handle the ReferenceRecord "created" event.
     */
    public function created(ReferenceRecord $referenceRecord): void
    {
        Cache::forget('tasas_bancos_' . $referenceRecord->date);
    }

    /**
     * Handle the ReferenceRecord "updated" event.
     */
    public function updated(ReferenceRecord $referenceRecord): void
    {
        Cache::forget('tasas_bancos_' . $referenceRecord->date);
    }

    /**
     * Handle the ReferenceRecord "deleted" event.
     */
    public function deleted(ReferenceRecord $referenceRecord): void
    {
        Cache::forget('tasas_bancos_' . $referenceRecord->date);
    }

    /**
     * Handle the ReferenceRecord "restored" event.
     */
    public function restored(ReferenceRecord $referenceRecord): void
    {
        Cache::forget('tasas_bancos_' . $referenceRecord->date);
    }

    /**
     * Handle the ReferenceRecord "force deleted" event.
     */
    public function forceDeleted(ReferenceRecord $referenceRecord): void
    {
        Cache::forget('tasas_bancos_' . $referenceRecord->date);
    }

    public function saved(ReferenceRecord $referenceRecord): void
    {

        Cache::forget('tasas_bancos_' . $referenceRecord->date);
    }
}
