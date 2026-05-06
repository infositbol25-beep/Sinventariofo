<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->enum('tipo_ingreso', ['COMPRA', 'AJUSTE_INICIAL', 'DEVOLUCION_INTERNA', 'OTRO'])->default('COMPRA');
            $table->string('proveedor', 150)->nullable();
            $table->string('documento_referencia', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('estado', ['REGISTRADA', 'ANULADA'])->default('REGISTRADA');
            $table->timestamps();

            $table->index(['fecha', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};