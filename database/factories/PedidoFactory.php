<?php

namespace Database\Factories;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition(): array
    {
        return [
            'cliente_id' => null,
            'fecha_entrega' => fake()->dateTimeBetween('-10 days', '+10 days'),
            'total' => 0,
            'estado' => fake()->randomElement(['pendiente', 'entregado', 'cancelado']),
        ];
    }
}