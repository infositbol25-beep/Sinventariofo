<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tecnicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 150);
            $table->string('ci', 20)->unique();
            $table->string('telefono', 30)->nullable();
            $table->string('cargo', 100)->default('Técnico');
            $table->string('cuadrilla', 100)->nullable();
            $table->string('zona', 100)->nullable();
            $table->boolean('estado')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['estado', 'nombre_completo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tecnicos');
    }
};