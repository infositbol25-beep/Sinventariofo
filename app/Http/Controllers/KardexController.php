<?php

namespace App\Http\Controllers;

use App\Models\DetalleDevolucion;
use App\Models\DetalleEntrada;
use App\Models\DetalleSalida;
use App\Models\Producto;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    private function puedeGestionar(): void
    {
        if (!auth()->check() || !in_array(auth()->user()->rol, ['Administrador', 'Almacenero'])) {
            abort(403, 'No autorizado.');
        }
    }

    public function index(Request $request)
    {
        $this->puedeGestionar();

        $productos = Producto::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $producto = null;
        $movimientos = [];
        $saldoInicial = 0;
        $saldoFinal = 0;
        $totalEntradas = 0;
        $totalSalidas = 0;
        $totalDevoluciones = 0;

        $productoId = $request->producto_id;
        $fechaDesde = $request->fecha_desde;
        $fechaHasta = $request->fecha_hasta;

        if ($productoId) {
            $producto = Producto::findOrFail($productoId);

            // SALDO INICIAL ANTES DEL RANGO
            if ($fechaDesde) {
                $entradasAntes = DetalleEntrada::where('producto_id', $producto->id)
                    ->whereHas('entrada', function ($q) use ($fechaDesde) {
                        $q->where('estado', 'REGISTRADA')
                          ->whereDate('fecha', '<', $fechaDesde);
                    })
                    ->sum('cantidad');

                $salidasAntes = DetalleSalida::where('producto_id', $producto->id)
                    ->whereHas('salida', function ($q) use ($fechaDesde) {
                        $q->where('estado', 'REGISTRADA')
                          ->whereDate('fecha', '<', $fechaDesde);
                    })
                    ->sum('cantidad');

                $devolucionesAntes = DetalleDevolucion::where('producto_id', $producto->id)
                    ->whereHas('devolucion', function ($q) use ($fechaDesde) {
                        $q->where('estado', 'REGISTRADA')
                          ->whereDate('fecha', '<', $fechaDesde);
                    })
                    ->sum('cantidad');

                $saldoInicial = (float)$entradasAntes - (float)$salidasAntes + (float)$devolucionesAntes;
            }

            // ENTRADAS DEL PERIODO
            $queryEntradas = DetalleEntrada::with(['entrada.usuario'])
                ->where('producto_id', $producto->id)
                ->whereHas('entrada', function ($q) use ($fechaDesde, $fechaHasta) {
                    $q->where('estado', 'REGISTRADA');

                    if ($fechaDesde) {
                        $q->whereDate('fecha', '>=', $fechaDesde);
                    }

                    if ($fechaHasta) {
                        $q->whereDate('fecha', '<=', $fechaHasta);
                    }
                })
                ->get();

            foreach ($queryEntradas as $detalle) {
                $movimientos[] = [
                    'fecha' => $detalle->entrada->fecha,
                    'tipo' => 'ENTRADA',
                    'referencia' => 'Entrada #' . $detalle->entrada->id,
                    'detalle' => $detalle->entrada->tipo_ingreso,
                    'entrada' => (float)$detalle->cantidad,
                    'salida' => 0,
                    'responsable' => $detalle->entrada->usuario?->name,
                    'observacion' => $detalle->entrada->observaciones,
                    'orden' => $detalle->entrada->created_at,
                ];

                $totalEntradas += (float)$detalle->cantidad;
            }

            // SALIDAS DEL PERIODO
            $querySalidas = DetalleSalida::with(['salida.usuario', 'salida.tecnico'])
                ->where('producto_id', $producto->id)
                ->whereHas('salida', function ($q) use ($fechaDesde, $fechaHasta) {
                    $q->where('estado', 'REGISTRADA');

                    if ($fechaDesde) {
                        $q->whereDate('fecha', '>=', $fechaDesde);
                    }

                    if ($fechaHasta) {
                        $q->whereDate('fecha', '<=', $fechaHasta);
                    }
                })
                ->get();

            foreach ($querySalidas as $detalle) {
                $movimientos[] = [
                    'fecha' => $detalle->salida->fecha,
                    'tipo' => 'SALIDA',
                    'referencia' => 'Salida #' . $detalle->salida->id,
                    'detalle' => 'Técnico: ' . ($detalle->salida->tecnico?->nombre_completo ?? '—'),
                    'entrada' => 0,
                    'salida' => (float)$detalle->cantidad,
                    'responsable' => $detalle->salida->usuario?->name,
                    'observacion' => $detalle->salida->observaciones,
                    'orden' => $detalle->salida->created_at,
                ];

                $totalSalidas += (float)$detalle->cantidad;
            }

            // DEVOLUCIONES DEL PERIODO
            $queryDevoluciones = DetalleDevolucion::with(['devolucion.usuario', 'devolucion.salida.tecnico'])
                ->where('producto_id', $producto->id)
                ->whereHas('devolucion', function ($q) use ($fechaDesde, $fechaHasta) {
                    $q->where('estado', 'REGISTRADA');

                    if ($fechaDesde) {
                        $q->whereDate('fecha', '>=', $fechaDesde);
                    }

                    if ($fechaHasta) {
                        $q->whereDate('fecha', '<=', $fechaHasta);
                    }
                })
                ->get();

            foreach ($queryDevoluciones as $detalle) {
                $movimientos[] = [
                    'fecha' => $detalle->devolucion->fecha,
                    'tipo' => 'DEVOLUCIÓN',
                    'referencia' => 'Devolución #' . $detalle->devolucion->id,
                    'detalle' => 'Técnico: ' . ($detalle->devolucion->salida?->tecnico?->nombre_completo ?? '—'),
                    'entrada' => (float)$detalle->cantidad,
                    'salida' => 0,
                    'responsable' => $detalle->devolucion->usuario?->name,
                    'observacion' => $detalle->devolucion->observaciones,
                    'orden' => $detalle->devolucion->created_at,
                ];

                $totalDevoluciones += (float)$detalle->cantidad;
            }

            usort($movimientos, function ($a, $b) {
                $fechaA = strtotime($a['fecha'] . ' ' . $a['orden']);
                $fechaB = strtotime($b['fecha'] . ' ' . $b['orden']);
                return $fechaA <=> $fechaB;
            });

            $saldo = $saldoInicial;

            foreach ($movimientos as &$movimiento) {
                $saldo += $movimiento['entrada'];
                $saldo -= $movimiento['salida'];
                $movimiento['saldo'] = $saldo;
            }

            $saldoFinal = $saldo;
        }

        return view('kardex.index', compact(
            'productos',
            'producto',
            'movimientos',
            'saldoInicial',
            'saldoFinal',
            'totalEntradas',
            'totalSalidas',
            'totalDevoluciones',
            'fechaDesde',
            'fechaHasta'
        ));
    }
}