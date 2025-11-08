<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrcamentoLocal extends Model
{
    use HasFactory;

    protected $table = 'orcamento_locais';

    protected $fillable = [
        'orcamento_id',
        'nome',
        'ordem',
        'total_material',
        'total_servico',
        'total_custos_extras',
        'total',
        'observacoes',
    ];

    protected $casts = [
        'total_material' => 'decimal:2',
        'total_servico' => 'decimal:2',
        'total_custos_extras' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function pecas(): HasMany
    {
        return $this->hasMany(OrcamentoPeca::class);
    }
}

