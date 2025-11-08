<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrcamentoPeca extends Model
{
    use HasFactory;

    protected $table = 'orcamento_pecas';

    protected $fillable = [
        'orcamento_local_id',
        'material_id',
        'material_price_id',
        'identificador',
        'largura_mm',
        'comprimento_mm',
        'espessura_mm',
        'quantidade',
        'area_m2',
        'perimetro_ml',
        'preco_material_unitario',
        'preco_material_total',
        'preco_servico_total',
        'custos_extras',
        'total',
        'observacoes',
    ];

    protected $casts = [
        'largura_mm' => 'decimal:2',
        'comprimento_mm' => 'decimal:2',
        'espessura_mm' => 'decimal:2',
        'quantidade' => 'integer',
        'area_m2' => 'decimal:3',
        'perimetro_ml' => 'decimal:3',
        'preco_material_unitario' => 'decimal:2',
        'preco_material_total' => 'decimal:2',
        'preco_servico_total' => 'decimal:2',
        'custos_extras' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function local(): BelongsTo
    {
        return $this->belongsTo(OrcamentoLocal::class, 'orcamento_local_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function materialPrice(): BelongsTo
    {
        return $this->belongsTo(MaterialPrice::class);
    }

    public function servicos(): HasMany
    {
        return $this->hasMany(OrcamentoPecaServico::class, 'orcamento_peca_id');
    }

    public function getAreaTotalAttribute(): float
    {
        return (float) $this->area_m2 * max($this->quantidade, 1);
    }
}

