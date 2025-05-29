<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrcamentoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'orcamento_id',
        'material_id',
        'servico_id',
        'descricao',
        'quantidade_m2',
        'subtotal',
    ];

    protected $casts = [
        'quantidade_m2' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}

