import React, { useEffect, useRef, useState } from 'react';

export default function DashboardApp() {
    const [loading, setLoading] = useState(true);
    const [pedidos, setPedidos] = useState([]);
    const [filtro, setFiltro] = useState('por-enviar');
    const [page, setPage] = useState(1);
    const [resumen, setResumen] = useState({});
    const [meta, setMeta] = useState({});
    const [links, setLinks] = useState({ prev: null, next: null });
    const [error, setError] = useState(null);

    // cache en memoria por combinación filtro|page
    const cacheRef = useRef(new Map());

    useEffect(() => {
        const controller = new AbortController();
        const cacheKey = `${filtro}|${page}`;

        const fetchPedidos = async () => {
            setError(null);

            // si ya existe en cache, úsalo y evita request
            if (cacheRef.current.has(cacheKey)) {
                const cached = cacheRef.current.get(cacheKey);
                setPedidos(cached.data);
                setResumen(cached.resumen);
                setMeta(cached.meta);
                setLinks(cached.links);
                setLoading(false);
                return;
            }

            try {
                setLoading(true);

                const response = await fetch(
                    `/dashboard/data?estado=${encodeURIComponent(filtro)}&page=${page}`,
                    {
                        headers: {
                            Accept: 'application/json',
                        },
                        credentials: 'same-origin',
                        signal: controller.signal,
                    }
                );

                if (!response.ok) {
                    throw new Error(`Error HTTP ${response.status}`);
                }

                const result = await response.json();

                const payload = {
                    data: result.data ?? [],
                    resumen: result.resumen ?? {},
                    meta: result.meta ?? {},
                    links: result.links ?? { prev: null, next: null },
                };

                cacheRef.current.set(cacheKey, payload);

                setPedidos(payload.data);
                setResumen(payload.resumen);
                setMeta(payload.meta);
                setLinks(payload.links);

                // Prefetch opcional de la siguiente página
                const nextPage = (payload.meta.current_page ?? page) + 1;
                const lastPage = payload.meta.last_page ?? page;

                if (nextPage <= lastPage) {
                    const nextKey = `${filtro}|${nextPage}`;

                    if (!cacheRef.current.has(nextKey)) {
                        fetch(
                            `/dashboard/data?estado=${encodeURIComponent(filtro)}&page=${nextPage}`,
                            {
                                headers: {
                                    Accept: 'application/json',
                                },
                                credentials: 'same-origin',
                            }
                        )
                            .then((r) => (r.ok ? r.json() : null))
                            .then((nextResult) => {
                                if (!nextResult) return;

                                cacheRef.current.set(nextKey, {
                                    data: nextResult.data ?? [],
                                    resumen: nextResult.resumen ?? {},
                                    meta: nextResult.meta ?? {},
                                    links: nextResult.links ?? { prev: null, next: null },
                                });
                            })
                            .catch(() => {
                                // ignorar errores del prefetch
                            });
                    }
                }
            } catch (err) {
                if (err.name === 'AbortError') return;
                setError('No se pudieron cargar los pedidos.');
            } finally {
                setLoading(false);
            }
        };

        fetchPedidos();

        return () => controller.abort();
    }, [filtro, page]);

    const cambiarFiltro = (nuevoFiltro) => {
        if (nuevoFiltro === filtro) return;
        setFiltro(nuevoFiltro);
        setPage(1);
    };

    const irPaginaAnterior = () => {
        if (meta.current_page > 1) {
            setPage(meta.current_page - 1);
        }
    };

    const irPaginaSiguiente = () => {
        if (meta.current_page < meta.last_page) {
            setPage(meta.current_page + 1);
        }
    };

    const badgeClass = (estado) => {
        switch (estado) {
            case 'entregado':
                return 'bg-green-100 text-green-800';
            case 'cancelado':
                return 'bg-gray-200 text-gray-800';
            default:
                return 'bg-yellow-100 text-yellow-800';
        }
    };

    return (
        <div className="max-w-7xl mx-auto py-8 px-4">
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-gray-900">Dashboard de Logística</h1>
                <p className="text-gray-600 mt-2">Panel interno de seguimiento de pedidos</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <button
                    onClick={() => cambiarFiltro('por-enviar')}
                    className={`bg-white rounded-xl shadow p-4 border text-left ${filtro === 'por-enviar' ? 'border-blue-500' : 'border-transparent'}`}
                >
                    <div className="text-sm text-gray-500">Por Enviar</div>
                    <div className="text-2xl font-bold text-blue-600">{resumen.por_enviar ?? 0}</div>
                </button>

                <button
                    onClick={() => cambiarFiltro('retrasados')}
                    className={`bg-white rounded-xl shadow p-4 border text-left ${filtro === 'retrasados' ? 'border-red-500' : 'border-transparent'}`}
                >
                    <div className="text-sm text-gray-500">Retrasados</div>
                    <div className="text-2xl font-bold text-red-600">{resumen.retrasados ?? 0}</div>
                </button>

                <button
                    onClick={() => cambiarFiltro('entregados')}
                    className={`bg-white rounded-xl shadow p-4 border text-left ${filtro === 'entregados' ? 'border-green-500' : 'border-transparent'}`}
                >
                    <div className="text-sm text-gray-500">Entregados</div>
                    <div className="text-2xl font-bold text-green-600">{resumen.entregados ?? 0}</div>
                </button>

                <button
                    onClick={() => cambiarFiltro('cancelados')}
                    className={`bg-white rounded-xl shadow p-4 border text-left ${filtro === 'cancelados' ? 'border-gray-500' : 'border-transparent'}`}
                >
                    <div className="text-sm text-gray-500">Cancelados</div>
                    <div className="text-2xl font-bold text-gray-700">{resumen.cancelados ?? 0}</div>
                </button>
            </div>

            <div className="bg-white rounded-xl shadow overflow-hidden">
                {loading ? (
                    <div className="p-8 text-center text-gray-500">Cargando pedidos...</div>
                ) : error ? (
                    <div className="p-8 text-center text-red-600">{error}</div>
                ) : (
                    <>
                        <div className="overflow-x-auto">
                            <table className="min-w-full text-sm text-left">
                                <thead className="bg-gray-50 border-b">
                                    <tr>
                                        <th className="px-4 py-3 font-semibold text-gray-700">ID</th>
                                        <th className="px-4 py-3 font-semibold text-gray-700">Cliente</th>
                                        <th className="px-4 py-3 font-semibold text-gray-700">Productos</th>
                                        <th className="px-4 py-3 font-semibold text-gray-700">Total</th>
                                        <th className="px-4 py-3 font-semibold text-gray-700">Fecha Entrega</th>
                                        <th className="px-4 py-3 font-semibold text-gray-700">Estado</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100">
                                    {pedidos.length > 0 ? (
                                        pedidos.map((pedido) => (
                                            <tr key={pedido.id} className="hover:bg-gray-50">
                                                <td className="px-4 py-3">{pedido.id}</td>
                                                <td className="px-4 py-3">
                                                    <div className="font-medium text-gray-900">{pedido.cliente.nombre}</div>
                                                    <div className="text-xs text-gray-500">{pedido.cliente.email}</div>
                                                </td>
                                                <td className="px-4 py-3">
                                                    <div className="flex flex-wrap gap-1">
                                                        {pedido.productos.map((producto) => (
                                                            <span
                                                                key={producto.id}
                                                                className="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800"
                                                            >
                                                                {producto.nombre}
                                                            </span>
                                                        ))}
                                                    </div>
                                                </td>
                                                <td className="px-4 py-3 font-semibold text-gray-900">
                                                    ${Number(pedido.total).toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                                                </td>
                                                <td className="px-4 py-3 text-gray-700">{pedido.fecha_entrega_formatted}</td>
                                                <td className="px-4 py-3">
                                                    <span className={`inline-flex items-center px-2 py-1 rounded-full text-xs ${badgeClass(pedido.estado)}`}>
                                                        {pedido.estado}
                                                    </span>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="6" className="px-4 py-6 text-center text-gray-500">
                                                No hay pedidos para este filtro.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>

                        <div className="flex items-center justify-between px-4 py-4 border-t bg-gray-50">
                            <div className="text-sm text-gray-600">
                                Mostrando {meta.from ?? 0} a {meta.to ?? 0} de {meta.total ?? 0} pedidos
                            </div>

                            <div className="flex gap-2">
                                <button
                                    onClick={irPaginaAnterior}
                                    disabled={!meta.current_page || meta.current_page <= 1}
                                    className="px-4 py-2 rounded-lg border bg-white text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Anterior
                                </button>

                                <span className="px-4 py-2 text-sm text-gray-700">
                                    Página {meta.current_page ?? 1} de {meta.last_page ?? 1}
                                </span>

                                <button
                                    onClick={irPaginaSiguiente}
                                    disabled={!meta.last_page || meta.current_page >= meta.last_page}
                                    className="px-4 py-2 rounded-lg border bg-white text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Siguiente
                                </button>
                            </div>
                        </div>
                    </>
                )}
            </div>
        </div>
    );
}