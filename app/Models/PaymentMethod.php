<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nome',
        'permite_parcelamento',
        'max_parcelas',
        'taxa_percentual',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'permite_parcelamento' => 'boolean',
        'taxa_percentual' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function orcamentos(): HasMany
    {
        return $this->hasMany(Orcamento::class);
    }
}

