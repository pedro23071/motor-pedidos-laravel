<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Pedidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard de Logística</h1>
            <p class="text-gray-600 mt-2">Panel interno de seguimiento de pedidos</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('dashboard', ['estado' => 'por-enviar']) }}"
               class="bg-white rounded-xl shadow p-4 border {{ $filtro === 'por-enviar' ? 'border-blue-500' : 'border-transparent' }}">
                <div class="text-sm text-gray-500">Por Enviar</div>
                <div class="text-2xl font-bold text-blue-600">{{ $resumen['por_enviar'] }}</div>
            </a>

            <a href="{{ route('dashboard', ['estado' => 'retrasados']) }}"
               class="bg-white rounded-xl shadow p-4 border {{ $filtro === 'retrasados' ? 'border-red-500' : 'border-transparent' }}">
                <div class="text-sm text-gray-500">Retrasados</div>
                <div class="text-2xl font-bold text-red-600">{{ $resumen['retrasados'] }}</div>
            </a>

            <a href="{{ route('dashboard', ['estado' => 'entregados']) }}"
               class="bg-white rounded-xl shadow p-4 border {{ $filtro === 'entregados' ? 'border-green-500' : 'border-transparent' }}">
                <div class="text-sm text-gray-500">Entregados</div>
                <div class="text-2xl font-bold text-green-600">{{ $resumen['entregados'] }}</div>
            </a>

            <a href="{{ route('dashboard', ['estado' => 'cancelados']) }}"
               class="bg-white rounded-xl shadow p-4 border {{ $filtro === 'cancelados' ? 'border-gray-500' : 'border-transparent' }}">
                <div class="text-sm text-gray-500">Cancelados</div>
                <div class="text-2xl font-bold text-gray-700">{{ $resumen['cancelados'] }}</div>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-700">ID</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Cliente</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Productos</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Total</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Fecha Entrega</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pedidos as $pedido)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $pedido->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $pedido->cliente->nombre }}</div>
                                    <div class="text-xs text-gray-500">{{ $pedido->cliente->email }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($pedido->productos as $producto)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                {{ $producto->nombre }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-900">
                                    ${{ number_format($pedido->total, 2) }}
                                </td>
                                <td class="px-4 py-3 text-gray-700">
                                    {{ $pedido->fecha_entrega->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs
                                        @if($pedido->estado === 'entregado') bg-green-100 text-green-800
                                        @elseif($pedido->estado === 'cancelado') bg-gray-200 text-gray-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    No hay pedidos para este filtro.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t bg-gray-50">
                {{ $pedidos->links() }}
            </div>
        </div>
    </div>
</body>
</html>