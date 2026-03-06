<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reference_records', function (Blueprint $table) {
            // índices para mejorar búsquedas por fecha y fuente+fecha
            $table->index('date');
            $table->index(['source', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reference_records', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['source', 'date']);
        });
    }
};
