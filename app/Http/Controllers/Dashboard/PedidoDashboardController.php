<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoDashboardController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->get('estado', 'por-enviar');

        $query = Pedido::query()
            ->with(['cliente', 'productos']);

        $query = match ($filtro) {
            'retrasados' => $query->retrasados(),
            'entregados' => $query->entregados(),
            'cancelados' => $query->cancelados(),
            default => $query->porEnviar(),
        };

        $pedidos = $query
            ->orderBy('fecha_entrega')
            ->paginate(20)
            ->withQueryString();

        $resumen = [
            'por_enviar' => Pedido::porEnviar()->count(),
            'retrasados' => Pedido::retrasados()->count(),
            'entregados' => Pedido::entregados()->count(),
            'cancelados' => Pedido::cancelados()->count(),
        ];

        return view('dashboard.index', compact('pedidos', 'filtro', 'resumen'));
    }


    public function react()
    {
        return view('dashboard.react');
    }

    public function data(Request $request)
    {
        $filtro = $request->get('estado', 'por-enviar');

        $query = Pedido::query()->with(['cliente', 'productos']);

        $query = match ($filtro) {
            'retrasados' => $query->retrasados(),
            'entregados' => $query->entregados(),
            'cancelados' => $query->cancelados(),
            default => $query->porEnviar(),
        };

        $pedidos = $query
            ->orderBy('fecha_entrega')
            ->paginate(20)
            ->withQueryString();

        $pedidosTransformados = $pedidos->through(function ($pedido) {
            return [
                'id' => $pedido->id,
                'estado' => $pedido->estado,
                'total' => $pedido->total,
                'fecha_entrega_formatted' => $pedido->fecha_entrega->format('Y-m-d H:i'),
                'cliente' => [
                    'id' => $pedido->cliente->id,
                    'nombre' => $pedido->cliente->nombre,
                    'email' => $pedido->cliente->email,
                ],
                'productos' => $pedido->productos->map(fn ($producto) => [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                ])->values(),
            ];
        });

        return response()->json([
            'data' => $pedidosTransformados->items(),
            'filtro' => $filtro,
            'resumen' => [
                'por_enviar' => Pedido::porEnviar()->count(),
                'retrasados' => Pedido::retrasados()->count(),
                'entregados' => Pedido::entregados()->count(),
                'cancelados' => Pedido::cancelados()->count(),
            ],
            'meta' => [
                'current_page' => $pedidos->currentPage(),
                'last_page' => $pedidos->lastPage(),
                'per_page' => $pedidos->perPage(),
                'total' => $pedidos->total(),
                'from' => $pedidos->firstItem(),
                'to' => $pedidos->lastItem(),
            ],
            'links' => [
                'prev' => $pedidos->previousPageUrl(),
                'next' => $pedidos->nextPageUrl(),
            ],
        ]);
    }

}