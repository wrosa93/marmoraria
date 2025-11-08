<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nome',
        'tipo_cobranca',
        'unidade_medida',
        'ativo',
        'descricao',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function prices()
    {
        return $this->hasMany(ServicoPrice::class);
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
            ->orderByDesc('data_inicio')
            ->first();
    }

    public function pecaServicos()
    {
        return $this->hasMany(OrcamentoPecaServico::class);
    }
}

