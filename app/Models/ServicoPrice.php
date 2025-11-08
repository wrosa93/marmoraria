<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicoPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'servico_id',
        'data_inicio',
        'data_fim',
        'preco',
        'moeda',
        'observacao',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'preco' => 'decimal:2',
    ];

    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class);
    }

    public function scopeVigente($query, ?string $date = null)
    {
        $referenceDate = $date ? \Illuminate\Support\Carbon::parse($date) : now();

        return $query->where('data_inicio', '<=', $referenceDate->toDateString())
            ->where(function ($query) use ($referenceDate) {
                $query->whereNull('data_fim')
                    ->orWhere('data_fim', '>=', $referenceDate->toDateString());
            });
    }
}

