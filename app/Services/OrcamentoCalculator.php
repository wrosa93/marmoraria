<?php

namespace App\Services;

use App\Models\Orcamento;
use Illuminate\Support\Facades\DB;

class OrcamentoCalculator
{
    public function recalcular(Orcamento $orcamento): Orcamento
    {
        return DB::transaction(function () use ($orcamento) {
            $orcamento->loadMissing([
                'locais.pecas.servicos',
            ]);

            $totalMaterial = 0;
            $totalServicos = 0;
            $totalExtras = 0;

            foreach ($orcamento->locais as $local) {
                $localMaterial = 0;
                $localServicos = 0;
                $localExtras = 0;
                $localTotal = 0;

                foreach ($local->pecas as $peca) {
                    $servicoTotal = $peca->servicos->sum('total');
                    $peca->preco_servico_total = $servicoTotal;
                    $peca->total = round($peca->preco_material_total + $servicoTotal + $peca->custos_extras, 2);
                    $peca->save();

                    $localMaterial += $peca->preco_material_total;
                    $localServicos += $servicoTotal;
                    $localExtras += $peca->custos_extras;
                    $localTotal += $peca->total;
                }

                $local->fill([
                    'total_material' => round($localMaterial, 2),
                    'total_servico' => round($localServicos, 2),
                    'total_custos_extras' => round($localExtras, 2),
                    'total' => round($localTotal, 2),
                ])->save();

                $totalMaterial += $localMaterial;
                $totalServicos += $localServicos;
                $totalExtras += $localExtras;
            }

            $totalMaterial = round($totalMaterial, 2);
            $totalServicos = round($totalServicos, 2);
            $totalExtras = round($totalExtras, 2);
            $totalBruto = round($totalMaterial + $totalServicos + $totalExtras, 2);

            $totalDesconto = 0;
            $descontoPercentual = $orcamento->desconto_percentual ?? 0;
            $descontoValor = $orcamento->desconto_valor ?? 0;

            if ($orcamento->desconto_tipo === 'percentual') {
                $totalDesconto = round($totalBruto * ($descontoPercentual / 100), 2);
                $descontoValor = $totalDesconto;
            } elseif ($orcamento->desconto_tipo === 'valor') {
                $totalDesconto = min(round($descontoValor, 2), $totalBruto);
            }

            $totalAcrescimo = round($orcamento->acrescimo_valor ?? 0, 2);
            $totalLiquido = round(max($totalBruto - $totalDesconto + $totalAcrescimo, 0), 2);

            $orcamento->fill([
                'total_material' => $totalMaterial,
                'total_servico' => $totalServicos,
                'total_custos_extras' => $totalExtras,
                'total_bruto' => $totalBruto,
                'total_desconto' => $totalDesconto,
                'desconto_valor' => $descontoValor,
                'total_acrescimo' => $totalAcrescimo,
                'total_liquido' => $totalLiquido,
            ])->save();

            return $orcamento->fresh([
                'locais.pecas.servicos',
            ]);
        });
    }
}
