<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('startDate', Carbon::now()->subDays(30)->startOfDay());
        $endDate = $request->input('endDate', Carbon::now()->endOfDay());

        // --- GRÁFICOS ---
        // Gráfico de Ventas
        $salesData = Venta::select(
            DB::raw('SUM(sub_total) as total_sales'),
            DB::raw("DATE_TRUNC('day', fecha_venta) as day_date")
        )
        ->whereBetween('fecha_venta', [$startDate, $endDate])
        ->groupBy('day_date')
        ->orderBy('day_date', 'ASC')
        ->get();

        // Gráfico de Compras
        $purchasesData = Compra::select(
            DB::raw('SUM(monto_total) as total_purchases'),
            DB::raw("DATE_TRUNC('day', fecha_compra) as day_date")
        )
        ->whereBetween('fecha_compra', [$startDate, $endDate])
        ->groupBy('day_date')
        ->orderBy('day_date', 'ASC')
        ->get();

        // --- KPIs ---
        $valorTotalInventario = Producto::sum(DB::raw('precio_venta * stock_actual'));
        $productosActivos = Producto::where('estado', 1)->count();
        $productosBajoStock = Producto::where('stock_minimo', '>', 0)
                                     ->whereColumn('stock_actual', '<=', 'stock_minimo')
                                     ->count();
        $ventasHoy = Venta::whereDate('fecha_venta', Carbon::today())->sum('sub_total');

        // --- PREDICCIONES ---
        $prediccionVentasDiarias = Venta::join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->where('ventas.fecha_venta', '>', Carbon::now()->subDays(30))
            ->sum('detalle_ventas.cantidad') / 30;
        $prediccionVentasSemanales = Venta::join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->where('ventas.fecha_venta', '>', Carbon::now()->subMonths(3))
            ->sum('detalle_ventas.cantidad') / 12;
        $prediccionVentasMensuales = Venta::join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->where('ventas.fecha_venta', '>', Carbon::now()->subYear())
            ->sum('detalle_ventas.cantidad') / 12;

        // --- TOP 5 PRODUCTOS MÁS VENDIDOS ---
        $topProducts = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->select('productos.nombre', 'productos.imagen', DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'))
            ->whereBetween('detalle_ventas.created_at', [$startDate, $endDate])
            ->groupBy('productos.nombre', 'productos.imagen')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get();

        // --- TOP 5 CLIENTES MÁS FRECUENTES ---
        $frequentCustomers = DB::table('ventas')
            ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->select('clientes.nombre', 'clientes.imagen', DB::raw('COUNT(ventas.id) as total_compras'))
            ->whereBetween('ventas.fecha_venta', [$startDate, $endDate])
            ->groupBy('clientes.nombre', 'clientes.imagen')
            ->orderBy('total_compras', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            // KPIs
            'valorTotalInventario' => $valorTotalInventario ?? 0,
            'productosActivos' => $productosActivos ?? 0,
            'productosBajoStock' => $productosBajoStock ?? 0,
            'ventasHoy' => $ventasHoy ?? 0,
            // Predicciones
            'prediccionVentasDiarias' => round($prediccionVentasDiarias ?? 0, 2),
            'prediccionVentasSemanales' => round($prediccionVentasSemanales ?? 0, 2),
            'prediccionVentasMensuales' => round($prediccionVentasMensuales ?? 0, 2),
            // Gráficos
            'salesChart' => [
                'labels' => $salesData->pluck('day_date')->map(function($date) { return Carbon::parse($date)->format('d M'); }),
                'datasets' => [
                    ['label' => 'Ventas', 'data' => $salesData->pluck('total_sales')]
                ],
            ],
            'purchasesChart' => [
                'labels' => $purchasesData->pluck('day_date')->map(function($date) { return Carbon::parse($date)->format('d M'); }),
                'datasets' => [
                    ['label' => 'Compras', 'data' => $purchasesData->pluck('total_purchases')]
                ],
            ],
            // Tablas
            'topProducts' => $topProducts,
            'frequentCustomers' => $frequentCustomers,
        ]);
    }
}
