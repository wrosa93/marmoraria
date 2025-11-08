<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Orcamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'cliente_id',
        'payment_method_id',
        'data',
        'data_validade',
        'status',
        'desconto_tipo',
        'desconto_percentual',
        'desconto_valor',
        'acrescimo_valor',
        'total_material',
        'total_servico',
        'total_custos_extras',
        'total_bruto',
        'total_desconto',
        'total_acrescimo',
        'total_liquido',
        'data_base_precos',
        'responsavel',
        'condicoes_pagamento',
        'observacoes',
    ];

    protected $casts = [
        'data' => 'date',
        'data_validade' => 'date',
        'data_base_precos' => 'date',
        'desconto_percentual' => 'decimal:2',
        'desconto_valor' => 'decimal:2',
        'acrescimo_valor' => 'decimal:2',
        'total_material' => 'decimal:2',
        'total_servico' => 'decimal:2',
        'total_custos_extras' => 'decimal:2',
        'total_bruto' => 'decimal:2',
        'total_desconto' => 'decimal:2',
        'total_acrescimo' => 'decimal:2',
        'total_liquido' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Orcamento $orcamento) {
            if (empty($orcamento->numero)) {
                $orcamento->numero = static::generateNumero();
            }

            if (empty($orcamento->data)) {
                $orcamento->data = now()->toDateString();
            }

            if (empty($orcamento->data_base_precos)) {
                $orcamento->data_base_precos = $orcamento->data;
            }
        });
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function locais(): HasMany
    {
        return $this->hasMany(OrcamentoLocal::class)->orderBy('ordem');
    }

    public function pecas(): HasManyThrough
    {
        return $this->hasManyThrough(OrcamentoPeca::class, OrcamentoLocal::class);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function getTotalGeralAttribute(): float
    {
        return (float) $this->total_liquido;
    }

    public static function generateNumero(): string
    {
        $prefix = 'ORC-' . now()->format('Y');

        $lastNumero = static::whereYear('created_at', now()->year)
            ->orderByDesc('id')
            ->value('numero');

        if ($lastNumero && Str::startsWith($lastNumero, $prefix)) {
            $sequence = (int) Str::afterLast($lastNumero, '-') + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('%s-%04d', $prefix, $sequence);
    }
}

