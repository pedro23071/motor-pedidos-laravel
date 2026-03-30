<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->words(2, true),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####??')),
            'precio' => fake()->randomFloat(2, 50, 5000),
        ];
    }
}