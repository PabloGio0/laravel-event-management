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
        Schema::create('cotizacion_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId("cotizacion_id")
                ->constrained("cotizaciones", "id")
                ->cascadeOnDelete();
            $table->foreignId("servicio_id")
            ->constrained("servicios", "id")
            ->cascadeOnDelete();
            $table->unique(["cotizacion_id", "servicio_id"]);
            $table->integer("cantidad")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacion_servicio');
    }
};
