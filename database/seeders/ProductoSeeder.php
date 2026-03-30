<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            ['nombre' => 'Laptop Pro 14', 'sku' => 'SKU-0001', 'precio' => 24999.00],
            ['nombre' => 'Mouse Inalámbrico', 'sku' => 'SKU-0002', 'precio' => 499.00],
            ['nombre' => 'Teclado Mecánico', 'sku' => 'SKU-0003', 'precio' => 1299.00],
            ['nombre' => 'Monitor 24 Pulgadas', 'sku' => 'SKU-0004', 'precio' => 3899.00],
            ['nombre' => 'Manejo Especial', 'sku' => 'SKU-0005', 'precio' => 799.00],
            ['nombre' => 'Disco SSD 1TB', 'sku' => 'SKU-0006', 'precio' => 1799.00],
            ['nombre' => 'Memoria USB 128GB', 'sku' => 'SKU-0007', 'precio' => 299.00],
            ['nombre' => 'Dock USB-C', 'sku' => 'SKU-0008', 'precio' => 1499.00],
            ['nombre' => 'Impresora WiFi', 'sku' => 'SKU-0009', 'precio' => 2699.00],
            ['nombre' => 'Silla Ergonómica', 'sku' => 'SKU-0010', 'precio' => 4599.00],
            ['nombre' => 'Webcam HD', 'sku' => 'SKU-0011', 'precio' => 899.00],
            ['nombre' => 'Audífonos Bluetooth', 'sku' => 'SKU-0012', 'precio' => 1599.00],
            ['nombre' => 'Tablet 10', 'sku' => 'SKU-0013', 'precio' => 5999.00],
            ['nombre' => 'Smartphone Lite', 'sku' => 'SKU-0014', 'precio' => 6999.00],
            ['nombre' => 'Router AX', 'sku' => 'SKU-0015', 'precio' => 2299.00],
            ['nombre' => 'Cámara IP', 'sku' => 'SKU-0016', 'precio' => 1899.00],
            ['nombre' => 'Bocina Portátil', 'sku' => 'SKU-0017', 'precio' => 999.00],
            ['nombre' => 'Cable HDMI', 'sku' => 'SKU-0018', 'precio' => 199.00],
            ['nombre' => 'Regulador', 'sku' => 'SKU-0019', 'precio' => 1399.00],
            ['nombre' => 'Proyector Mini', 'sku' => 'SKU-0020', 'precio' => 5499.00],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}