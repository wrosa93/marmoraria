<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'data_inicio',
        'data_fim',
        'preco_m2',
        'moeda',
        'ativo',
        'observacao',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'preco_m2' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function scopeVigente($query, ?string $date = null)
    {
        $referenceDate = $date ? \Illuminate\Support\Carbon::parse($date) : now();

        return $query->where('data_inicio', '<=', $referenceDate->toDateString())
            ->where(function ($query) use ($referenceDate) {
                $query->whereNull('data_fim')
                    ->orWhere('data_fim', '>=', $referenceDate->toDateString());
            })
            ->where('ativo', true);
    }
}

