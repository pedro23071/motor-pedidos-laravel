<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ClientePedidoSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = Cliente::factory()->count(200)->create();

        $productos = Producto::all();

        Pedido::factory()
            ->count(1000)
            ->make()
            ->each(function ($pedido) use ($clientes, $productos) {
                $pedido->cliente_id = $clientes->random()->id;
                $pedido->save();

                $productosSeleccionados = $productos->random(rand(1, 5));

                $pedido->productos()->attach($productosSeleccionados->pluck('id')->toArray());

                $total = $productosSeleccionados->sum('precio');

                $pedido->update([
                    'total' => $total,
                ]);
            });
    }
}