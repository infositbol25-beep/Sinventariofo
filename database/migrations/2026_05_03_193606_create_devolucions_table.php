<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('salida_id')->constrained('salidas');
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('estado', ['REGISTRADA', 'ANULADA'])->default('REGISTRADA');
            $table->timestamps();

            $table->index(['fecha', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devoluciones');
    }
};