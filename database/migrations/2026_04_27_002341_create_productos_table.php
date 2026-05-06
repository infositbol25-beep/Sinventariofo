<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 150);
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->enum('unidad_medida', ['UND', 'M', 'ROLLO', 'CAJA', 'PAQUETE'])->default('UND');
            $table->decimal('stock_actual', 12, 2)->default(0);
            $table->decimal('stock_minimo', 12, 2)->default(0);
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->index(['estado', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};