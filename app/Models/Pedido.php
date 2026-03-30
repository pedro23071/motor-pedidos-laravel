<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'fecha_entrega',
        'total',
        'estado',
    ];

    protected $casts = [
        'fecha_entrega' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'pedido_producto')
            ->withTimestamps();
    }

    public function scopePorEnviar(Builder $query): Builder
    {
        return $query->where('estado', 'pendiente')
            ->whereDate('fecha_entrega', '>=', today())
            ->whereDate('fecha_entrega', '<=', today()->copy()->addDays(3));
    }

    public function scopeRetrasados(Builder $query): Builder
    {
        return $query->where('estado', 'pendiente')
            ->whereDate('fecha_entrega', '<', today());
    }

    public function scopeEntregados(Builder $query): Builder
    {
        return $query->where('estado', 'entregado');
    }

    public function scopeCancelados(Builder $query): Builder
    {
        return $query->where('estado', 'cancelado');
    }
}