<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrcamentoPecaServico extends Model
{
    use HasFactory;

    protected $table = 'orcamento_peca_servicos';

    protected $fillable = [
        'orcamento_peca_id',
        'servico_id',
        'servico_price_id',
        'descricao',
        'tipo_cobranca',
        'quantidade',
        'preco_unitario',
        'total',
    ];

    protected $casts = [
        'quantidade' => 'decimal:3',
        'preco_unitario' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function peca(): BelongsTo
    {
        return $this->belongsTo(OrcamentoPeca::class, 'orcamento_peca_id');
    }

    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class);
    }

    public function servicoPrice(): BelongsTo
    {
        return $this->belongsTo(ServicoPrice::class);
    }
}

