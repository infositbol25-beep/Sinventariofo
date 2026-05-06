<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SalidaController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/mi-perfil', [PerfilController::class, 'show'])->name('perfil.show');
    Route::put('/mi-perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/mi-password', [PerfilController::class, 'passwordForm'])->name('perfil.password.form');
    Route::put('/mi-password', [PerfilController::class, 'updatePassword'])->name('perfil.password.update');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    Route::patch('/usuarios/{usuario}/activate', [UsuarioController::class, 'activate'])->name('usuarios.activate');
    Route::patch('/usuarios/{usuario}/unlock', [UsuarioController::class, 'unlock'])->name('usuarios.unlock');

    Route::get('/tecnicos', [TecnicoController::class, 'index'])->name('tecnicos.index');
    Route::get('/tecnicos/create', [TecnicoController::class, 'create'])->name('tecnicos.create');
    Route::post('/tecnicos', [TecnicoController::class, 'store'])->name('tecnicos.store');
    Route::get('/tecnicos/{tecnico}/edit', [TecnicoController::class, 'edit'])->name('tecnicos.edit');
    Route::put('/tecnicos/{tecnico}', [TecnicoController::class, 'update'])->name('tecnicos.update');
    Route::delete('/tecnicos/{tecnico}', [TecnicoController::class, 'destroy'])->name('tecnicos.destroy');
    Route::patch('/tecnicos/{tecnico}/activate', [TecnicoController::class, 'activate'])->name('tecnicos.activate');

    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    Route::patch('/categorias/{categoria}/activate', [CategoriaController::class, 'activate'])->name('categorias.activate');

    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::patch('/productos/{producto}/activate', [ProductoController::class, 'activate'])->name('productos.activate');

    Route::get('/entradas', [EntradaController::class, 'index'])->name('entradas.index');
    Route::get('/entradas/create', [EntradaController::class, 'create'])->name('entradas.create');
    Route::post('/entradas', [EntradaController::class, 'store'])->name('entradas.store');
    Route::get('/entradas/{entrada}', [EntradaController::class, 'show'])->name('entradas.show');
    Route::delete('/entradas/{entrada}', [EntradaController::class, 'destroy'])->name('entradas.destroy');

    Route::get('/salidas', [SalidaController::class, 'index'])->name('salidas.index');
    Route::get('/salidas/create', [SalidaController::class, 'create'])->name('salidas.create');
    Route::post('/salidas', [SalidaController::class, 'store'])->name('salidas.store');
    Route::get('/salidas/{salida}', [SalidaController::class, 'show'])->name('salidas.show');
    Route::delete('/salidas/{salida}', [SalidaController::class, 'destroy'])->name('salidas.destroy');

    Route::get('/devoluciones', [DevolucionController::class, 'index'])->name('devoluciones.index');
    Route::get('/devoluciones/create', [DevolucionController::class, 'create'])->name('devoluciones.create');
    Route::post('/devoluciones', [DevolucionController::class, 'store'])->name('devoluciones.store');
    Route::get('/devoluciones/{devolucion}', [DevolucionController::class, 'show'])->name('devoluciones.show');
    Route::delete('/devoluciones/{devolucion}', [DevolucionController::class, 'destroy'])->name('devoluciones.destroy');

    Route::get('/kardex', [KardexController::class, 'index'])->name('kardex.index');

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/stock', [ReporteController::class, 'stock'])->name('reportes.stock');
    Route::get('/reportes/entradas', [ReporteController::class, 'entradas'])->name('reportes.entradas');
    Route::get('/reportes/salidas', [ReporteController::class, 'salidas'])->name('reportes.salidas');
    Route::get('/reportes/devoluciones', [ReporteController::class, 'devoluciones'])->name('reportes.devoluciones');

    Route::get('/reportes/conciliacion-tecnicos', [ReporteController::class, 'conciliacionTecnicos'])->name('reportes.conciliacion_tecnicos');
});