<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'preco_unitario',
    ];

    public function orcamentoItems()
    {
        return $this->hasMany(OrcamentoItem::class);
    }
}

