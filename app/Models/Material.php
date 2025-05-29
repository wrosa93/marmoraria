<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'tipo',
        'preco_m2',
        'espessura_mm',
    ];

    public function orcamentoItems()
    {
        return $this->hasMany(OrcamentoItem::class);
    }
}

