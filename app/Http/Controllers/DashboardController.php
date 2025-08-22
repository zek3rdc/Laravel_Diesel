<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\Compra;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // KPIs de Inventario
        $valorTotalInventario = Producto::sum(DB::raw('precio_venta * stock_actual'));
        $productosActivos = Producto::where('estado', 'activo')->count();
        $productosInactivos = Producto::where('estado', 'inactivo')->count();
        $productosBajoStock = Producto::where('stock_minimo', '>', 0)
                                     ->whereColumn('stock_actual', '<=', 'stock_minimo')
                                     ->count();

        // Productos próximos a vencer (en los próximos 30 días)
        $productosProximosAVencer = Producto::where('fecha_vencimiento', '<', Carbon::now()->addDays(30))->get();

        // Ventas de hoy
        $ventasHoy = Venta::whereBetween('fecha_venta', [$startDate, $endDate])->sum('sub_total');

        // Ventas predictivas
        $prediccionVentasDiariasData = Venta::join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->where('ventas.fecha_venta', '>', Carbon::now()->subDays(30))
            ->select(DB::raw('SUM(detalle_ventas.cantidad) as total_cantidad, SUM(ventas.sub_total) as total_valor'))
            ->first();
        $prediccionVentasDiarias = $prediccionVentasDiariasData->total_cantidad / 30;
        $prediccionValorDiario = $prediccionVentasDiariasData->total_valor / 30;

        $prediccionVentasSemanalesData = Venta::join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->where('ventas.fecha_venta', '>', Carbon::now()->subMonths(3))
            ->select(DB::raw('SUM(detalle_ventas.cantidad) as total_cantidad, SUM(ventas.sub_total) as total_valor'))
            ->first();
        $prediccionVentasSemanales = $prediccionVentasSemanalesData->total_cantidad / 12;
        $prediccionValorSemanal = $prediccionVentasSemanalesData->total_valor / 12;

        $prediccionVentasMensualesData = Venta::join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->where('ventas.fecha_venta', '>', Carbon::now()->subYear())
            ->select(DB::raw('SUM(detalle_ventas.cantidad) as total_cantidad, SUM(ventas.sub_total) as total_valor'))
            ->first();
        $prediccionVentasMensuales = $prediccionVentasMensualesData->total_cantidad / 12;
        $prediccionValorMensual = $prediccionVentasMensualesData->total_valor / 12;


        // Compras del mes a proveedores
        $comprasMes = Compra::whereMonth('fecha_compra', Carbon::now()->month)->sum('monto_total');

        // --- DATOS PARA GRÁFICOS DE VENTAS REALES ---

        // Gráfico 1: Ventas de Hoy (por hora)
        $ventasHoyData = Venta::select(
            DB::raw('EXTRACT(HOUR FROM fecha_venta) as hora'),
            DB::raw('SUM(sub_total) as total_valor'),
            DB::raw('COUNT(*) as total_ventas')
        )
        ->whereBetween('fecha_venta', [$startDate, $endDate])
        ->groupBy('hora')
        ->orderBy('hora', 'asc')
        ->get();

        $horasHoy = $ventasHoyData->pluck('hora')->map(fn($h) => $h . ':00');
        $montosHoy = $ventasHoyData->pluck('total_valor');
        $conteosHoy = $ventasHoyData->pluck('total_ventas');

        // Gráfico 2: Ventas de la Semana Actual (por día)
        $ventasSemanaData = Venta::select(
            DB::raw('DATE_TRUNC(\'day\', fecha_venta) as fecha'),
            DB::raw('SUM(sub_total) as total_valor'),
            DB::raw('COUNT(*) as total_ventas')
        )
        ->whereBetween('fecha_venta', [$startDate, $endDate])
        ->groupBy('fecha')
        ->orderBy('fecha', 'asc')
        ->get();

        $diasSemana = $ventasSemanaData->pluck('fecha')->map(fn($d) => Carbon::parse($d)->format('D d'));
        $montosSemana = $ventasSemanaData->pluck('total_valor');
        $conteosSemana = $ventasSemanaData->pluck('total_ventas');

        // Gráfico 3: Ventas del Mes Actual (por día)
        $ventasMesData = Venta::select(
            DB::raw('DATE_TRUNC(\'day\', fecha_venta) as fecha'),
            DB::raw('SUM(sub_total) as total_valor'),
            DB::raw('COUNT(*) as total_ventas')
        )
        ->whereBetween('fecha_venta', [$startDate, $endDate])
        ->groupBy('fecha')
        ->orderBy('fecha', 'asc')
        ->get();

        $diasMes = $ventasMesData->pluck('fecha')->map(fn($d) => Carbon::parse($d)->format('d'));
        $montosMes = $ventasMesData->pluck('total_valor');
        $conteosMes = $ventasMesData->pluck('total_ventas');


        return view('dashboard', compact(
            'startDate', 'endDate',
            'valorTotalInventario',
            'productosActivos',
            'productosInactivos',
            'productosBajoStock',
            'productosProximosAVencer',
            'ventasHoy',
            'prediccionVentasDiarias',
            'prediccionValorDiario',
            'prediccionVentasSemanales',
            'prediccionValorSemanal',
            'prediccionVentasMensuales',
            'prediccionValorMensual',
            'comprasMes',
            'horasHoy', 'montosHoy', 'conteosHoy',
            'diasSemana', 'montosSemana', 'conteosSemana',
            'diasMes', 'montosMes', 'conteosMes'
        ));
    }
}
