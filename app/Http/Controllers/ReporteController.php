<?php

namespace App\Http\Controllers;

use App\Models\Devolucion;
use App\Models\DetalleDevolucion;
use App\Models\DetalleEntrada;
use App\Models\DetalleSalida;
use App\Models\Entrada;
use App\Models\Producto;
use App\Models\Salida;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    private function puedeGestionar(): void
    {
        if (!auth()->check() || !in_array(auth()->user()->rol, ['Administrador', 'Almacenero'])) {
            abort(403, 'No autorizado.');
        }
    }

    public function index()
    {
        $this->puedeGestionar();

        return view('reportes.index');
    }

    public function stock(Request $request)
    {
        $this->puedeGestionar();

        $q = trim((string) $request->q);
        $soloBajo = $request->solo_bajo;

        $productos = Producto::with('categoria');

        if ($q !== '') {
            $productos->where(function ($query) use ($q) {
                $query->where('codigo', 'like', "%{$q}%")
                    ->orWhere('nombre', 'like', "%{$q}%")
                    ->orWhereHas('categoria', function ($subQuery) use ($q) {
                        $subQuery->where('nombre', 'like', "%{$q}%");
                    });
            });
        }

        if ($soloBajo) {
            $productos->whereColumn('stock_actual', '<=', 'stock_minimo')
                ->where('stock_minimo', '>', 0);
        }

        $productos = $productos->orderBy('nombre')->get();

        $totalProductos = Producto::count();
        $productosActivos = Producto::where('estado', true)->count();
        $productosBajoStock = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->where('stock_minimo', '>', 0)
            ->count();

        return view('reportes.stock', compact(
            'productos',
            'q',
            'soloBajo',
            'totalProductos',
            'productosActivos',
            'productosBajoStock'
        ));
    }

    public function entradas(Request $request)
    {
        $this->puedeGestionar();

        $productoId = $request->producto_id;
        $fechaDesde = $request->fecha_desde;
        $fechaHasta = $request->fecha_hasta;
        $estado = $request->estado;

        $productos = Producto::where('estado', true)->orderBy('nombre')->get();

        $detalles = DetalleEntrada::with(['entrada.usuario', 'producto']);

        if ($productoId) {
            $detalles->where('producto_id', $productoId);
        }

        $detalles->whereHas('entrada', function ($q) use ($fechaDesde, $fechaHasta, $estado) {
            if ($fechaDesde) {
                $q->whereDate('fecha', '>=', $fechaDesde);
            }

            if ($fechaHasta) {
                $q->whereDate('fecha', '<=', $fechaHasta);
            }

            if ($estado && in_array($estado, ['REGISTRADA', 'ANULADA'])) {
                $q->where('estado', $estado);
            }
        });

        $detalles = $detalles->orderByDesc('id')->get();

        $totalRegistros = $detalles->count();
        $totalCantidad = $detalles->sum(fn($item) => (float)$item->cantidad);

        return view('reportes.entradas', compact(
            'productos',
            'detalles',
            'productoId',
            'fechaDesde',
            'fechaHasta',
            'estado',
            'totalRegistros',
            'totalCantidad'
        ));
    }

    public function salidas(Request $request)
    {
        $this->puedeGestionar();

        $productoId = $request->producto_id;
        $tecnicoId = $request->tecnico_id;
        $fechaDesde = $request->fecha_desde;
        $fechaHasta = $request->fecha_hasta;
        $estado = $request->estado;

        $productos = Producto::where('estado', true)->orderBy('nombre')->get();
        $tecnicos = Tecnico::where('estado', true)->orderBy('nombre_completo')->get();

        $detalles = DetalleSalida::with(['salida.usuario', 'salida.tecnico', 'producto']);

        if ($productoId) {
            $detalles->where('producto_id', $productoId);
        }

        $detalles->whereHas('salida', function ($q) use ($tecnicoId, $fechaDesde, $fechaHasta, $estado) {
            if ($tecnicoId) {
                $q->where('tecnico_id', $tecnicoId);
            }

            if ($fechaDesde) {
                $q->whereDate('fecha', '>=', $fechaDesde);
            }

            if ($fechaHasta) {
                $q->whereDate('fecha', '<=', $fechaHasta);
            }

            if ($estado && in_array($estado, ['REGISTRADA', 'ANULADA'])) {
                $q->where('estado', $estado);
            }
        });

        $detalles = $detalles->orderByDesc('id')->get();

        $totalRegistros = $detalles->count();
        $totalCantidad = $detalles->sum(fn($item) => (float)$item->cantidad);

        return view('reportes.salidas', compact(
            'productos',
            'tecnicos',
            'detalles',
            'productoId',
            'tecnicoId',
            'fechaDesde',
            'fechaHasta',
            'estado',
            'totalRegistros',
            'totalCantidad'
        ));
    }

    public function devoluciones(Request $request)
    {
        $this->puedeGestionar();

        $productoId = $request->producto_id;
        $tecnicoId = $request->tecnico_id;
        $fechaDesde = $request->fecha_desde;
        $fechaHasta = $request->fecha_hasta;
        $estado = $request->estado;

        $productos = Producto::where('estado', true)->orderBy('nombre')->get();
        $tecnicos = Tecnico::where('estado', true)->orderBy('nombre_completo')->get();

        $detalles = DetalleDevolucion::with(['devolucion.usuario', 'devolucion.salida.tecnico', 'producto']);

        if ($productoId) {
            $detalles->where('producto_id', $productoId);
        }

        $detalles->whereHas('devolucion', function ($q) use ($tecnicoId, $fechaDesde, $fechaHasta, $estado) {
            if ($tecnicoId) {
                $q->whereHas('salida', function ($subQuery) use ($tecnicoId) {
                    $subQuery->where('tecnico_id', $tecnicoId);
                });
            }

            if ($fechaDesde) {
                $q->whereDate('fecha', '>=', $fechaDesde);
            }

            if ($fechaHasta) {
                $q->whereDate('fecha', '<=', $fechaHasta);
            }

            if ($estado && in_array($estado, ['REGISTRADA', 'ANULADA'])) {
                $q->where('estado', $estado);
            }
        });

        $detalles = $detalles->orderByDesc('id')->get();

        $totalRegistros = $detalles->count();
        $totalCantidad = $detalles->sum(fn($item) => (float)$item->cantidad);

        return view('reportes.devoluciones', compact(
            'productos',
            'tecnicos',
            'detalles',
            'productoId',
            'tecnicoId',
            'fechaDesde',
            'fechaHasta',
            'estado',
            'totalRegistros',
            'totalCantidad'
        ));
    }
    public function conciliacionTecnicos(Request $request)
{
    $this->puedeGestionar();

    $tecnicoId = $request->tecnico_id;
    $productoId = $request->producto_id;
    $fechaDesde = $request->fecha_desde;
    $fechaHasta = $request->fecha_hasta;
    $soloPendientes = $request->solo_pendientes;

    $tecnicos = \App\Models\Tecnico::where('estado', true)
        ->orderBy('nombre_completo')
        ->get();

    $productos = \App\Models\Producto::where('estado', true)
        ->orderBy('nombre')
        ->get();

    $queryEntregados = DB::table('detalle_salidas as ds')
        ->join('salidas as s', 's.id', '=', 'ds.salida_id')
        ->join('tecnicos as t', 't.id', '=', 's.tecnico_id')
        ->join('productos as p', 'p.id', '=', 'ds.producto_id')
        ->where('s.estado', 'REGISTRADA');

    if ($tecnicoId) {
        $queryEntregados->where('s.tecnico_id', $tecnicoId);
    }

    if ($productoId) {
        $queryEntregados->where('ds.producto_id', $productoId);
    }

    if ($fechaDesde) {
        $queryEntregados->whereDate('s.fecha', '>=', $fechaDesde);
    }

    if ($fechaHasta) {
        $queryEntregados->whereDate('s.fecha', '<=', $fechaHasta);
    }

    $entregados = $queryEntregados
        ->selectRaw("
            t.id as tecnico_id,
            t.nombre_completo as tecnico_nombre,
            p.id as producto_id,
            p.codigo as producto_codigo,
            p.nombre as producto_nombre,
            p.unidad_medida as unidad_medida,
            SUM(ds.cantidad) as total_entregado
        ")
        ->groupBy('t.id', 't.nombre_completo', 'p.id', 'p.codigo', 'p.nombre', 'p.unidad_medida')
        ->get();

    $queryDevueltos = DB::table('detalle_devoluciones as dd')
        ->join('devoluciones as d', 'd.id', '=', 'dd.devolucion_id')
        ->join('salidas as s', 's.id', '=', 'd.salida_id')
        ->join('tecnicos as t', 't.id', '=', 's.tecnico_id')
        ->join('productos as p', 'p.id', '=', 'dd.producto_id')
        ->where('d.estado', 'REGISTRADA')
        ->where('s.estado', 'REGISTRADA');

    if ($tecnicoId) {
        $queryDevueltos->where('s.tecnico_id', $tecnicoId);
    }

    if ($productoId) {
        $queryDevueltos->where('dd.producto_id', $productoId);
    }

    if ($fechaDesde) {
        $queryDevueltos->whereDate('d.fecha', '>=', $fechaDesde);
    }

    if ($fechaHasta) {
        $queryDevueltos->whereDate('d.fecha', '<=', $fechaHasta);
    }

    $devueltos = $queryDevueltos
        ->selectRaw("
            t.id as tecnico_id,
            t.nombre_completo as tecnico_nombre,
            p.id as producto_id,
            p.codigo as producto_codigo,
            p.nombre as producto_nombre,
            p.unidad_medida as unidad_medida,
            SUM(dd.cantidad) as total_devuelto
        ")
        ->groupBy('t.id', 't.nombre_completo', 'p.id', 'p.codigo', 'p.nombre', 'p.unidad_medida')
        ->get();

    $detalleConciliacion = [];

    foreach ($entregados as $fila) {
        $clave = $fila->tecnico_id . '-' . $fila->producto_id;

        $detalleConciliacion[$clave] = [
            'tecnico_id' => $fila->tecnico_id,
            'tecnico_nombre' => $fila->tecnico_nombre,
            'producto_id' => $fila->producto_id,
            'producto_codigo' => $fila->producto_codigo,
            'producto_nombre' => $fila->producto_nombre,
            'unidad_medida' => $fila->unidad_medida,
            'entregado' => (float) $fila->total_entregado,
            'devuelto' => 0,
            'pendiente' => 0,
        ];
    }

    foreach ($devueltos as $fila) {
        $clave = $fila->tecnico_id . '-' . $fila->producto_id;

        if (!isset($detalleConciliacion[$clave])) {
            $detalleConciliacion[$clave] = [
                'tecnico_id' => $fila->tecnico_id,
                'tecnico_nombre' => $fila->tecnico_nombre,
                'producto_id' => $fila->producto_id,
                'producto_codigo' => $fila->producto_codigo,
                'producto_nombre' => $fila->producto_nombre,
                'unidad_medida' => $fila->unidad_medida,
                'entregado' => 0,
                'devuelto' => 0,
                'pendiente' => 0,
            ];
        }

        $detalleConciliacion[$clave]['devuelto'] = (float) $fila->total_devuelto;
    }

    foreach ($detalleConciliacion as &$fila) {
        $fila['pendiente'] = $fila['entregado'] - $fila['devuelto'];
    }
    unset($fila);

    $detalleConciliacion = array_values($detalleConciliacion);

    if ($soloPendientes) {
        $detalleConciliacion = array_values(array_filter($detalleConciliacion, function ($fila) {
            return $fila['pendiente'] > 0;
        }));
    }

    usort($detalleConciliacion, function ($a, $b) {
        return [$a['tecnico_nombre'], $a['producto_nombre']] <=> [$b['tecnico_nombre'], $b['producto_nombre']];
    });

    $resumenTecnicos = [];
    $totalEntregado = 0;
    $totalDevuelto = 0;
    $totalPendiente = 0;

    foreach ($detalleConciliacion as $fila) {
        $claveTecnico = $fila['tecnico_id'];

        if (!isset($resumenTecnicos[$claveTecnico])) {
            $resumenTecnicos[$claveTecnico] = [
                'tecnico_id' => $fila['tecnico_id'],
                'tecnico_nombre' => $fila['tecnico_nombre'],
                'entregado' => 0,
                'devuelto' => 0,
                'pendiente' => 0,
            ];
        }

        $resumenTecnicos[$claveTecnico]['entregado'] += $fila['entregado'];
        $resumenTecnicos[$claveTecnico]['devuelto'] += $fila['devuelto'];
        $resumenTecnicos[$claveTecnico]['pendiente'] += $fila['pendiente'];

        $totalEntregado += $fila['entregado'];
        $totalDevuelto += $fila['devuelto'];
        $totalPendiente += $fila['pendiente'];
    }

    $resumenTecnicos = array_values($resumenTecnicos);

    usort($resumenTecnicos, function ($a, $b) {
        return $a['tecnico_nombre'] <=> $b['tecnico_nombre'];
    });

    $tecnicosConPendiente = count(array_filter($resumenTecnicos, function ($fila) {
        return $fila['pendiente'] > 0;
    }));

    return view('reportes.conciliacion_tecnicos', compact(
        'tecnicos',
        'productos',
        'detalleConciliacion',
        'resumenTecnicos',
        'tecnicoId',
        'productoId',
        'fechaDesde',
        'fechaHasta',
        'soloPendientes',
        'totalEntregado',
        'totalDevuelto',
        'totalPendiente',
        'tecnicosConPendiente'
    ));
}
    
}