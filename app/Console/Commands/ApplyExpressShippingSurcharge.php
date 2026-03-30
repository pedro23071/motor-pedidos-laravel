<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use Illuminate\Console\Command;

class ApplyExpressShippingSurcharge extends Command
{
    protected $signature = 'orders:apply-express-surcharge';
    protected $description = 'Aplica un recargo del 10% a pedidos pendientes con entrega mañana y producto especial (id 5).';

    public function handle(): int
    {
        $tomorrow = today()->addDay();

        $query = Pedido::query()
            ->where('estado', 'pendiente')
            ->whereDate('fecha_entrega', $tomorrow)
            ->whereHas('productos', function ($query) {
                $query->where('productos.id', 5);
            });

        $totalPedidos = (clone $query)->count();

        $this->info("Pedidos encontrados para procesar: {$totalPedidos}");

        $processed = 0;

        $query->chunkById(200, function ($pedidos) use (&$processed) {
            foreach ($pedidos as $pedido) {
                $pedido->update([
                    'total' => round($pedido->total * 1.10, 2),
                ]);

                $processed++;
            }
        });

        $this->info("Pedidos procesados: {$processed}");

        return self::SUCCESS;
    }
}