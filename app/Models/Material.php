<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nome',
        'tipo',
        'acabamento',
        'cor',
        'espessura_padrao_mm',
        'ativo',
        'descricao',
    ];

    protected $casts = [
        'espessura_padrao_mm' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function prices()
    {
        return $this->hasMany(MaterialPrice::class);
    }

    public function currentPriceForDate(?string $date = null)
    {
        $referenceDate = $date ? \Illuminate\Support\Carbon::parse($date) : now();

        return $this->prices()
            ->where('data_inicio', '<=', $referenceDate->toDateString())
            ->where(function ($query) use ($referenceDate) {
                $query->whereNull('data_fim')
                    ->orWhere('data_fim', '>=', $referenceDate->toDateString());
            })
            ->where('ativo', true)
            ->orderByDesc('data_inicio')
            ->first();
    }

    public function pecas()
    {
        return $this->hasMany(OrcamentoPeca::class);
    }
}

