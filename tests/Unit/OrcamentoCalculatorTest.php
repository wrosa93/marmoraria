<?php

namespace Tests\Unit;

use App\Models\Cliente;
use App\Models\Material;
use App\Models\MaterialPrice;
use App\Models\Orcamento;
use App\Models\OrcamentoLocal;
use App\Models\OrcamentoPeca;
use App\Models\OrcamentoPecaServico;
use App\Models\Servico;
use App\Models\ServicoPrice;
use App\Services\OrcamentoCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OrcamentoCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_recalcula_totais_de_material_servico_e_extras()
    {
        $cliente = Cliente::factory()->create();

        $material = Material::factory()->create();
        $materialPrice = MaterialPrice::create([
            'material_id' => $material->id,
            'data_inicio' => Carbon::parse('2024-01-01'),
            'preco_m2' => 100,
            'moeda' => 'BRL',
        ]);

        $servico = Servico::factory()->create();
        $servicoPrice = ServicoPrice::create([
            'servico_id' => $servico->id,
            'data_inicio' => Carbon::parse('2024-01-01'),
            'preco' => 50,
            'moeda' => 'BRL',
        ]);

        $orcamento = Orcamento::create([
            'cliente_id' => $cliente->id,
            'payment_method_id' => null,
            'data' => Carbon::parse('2024-01-15'),
            'data_validade' => Carbon::parse('2024-02-15'),
            'status' => 'rascunho',
            'desconto_tipo' => 'percentual',
            'desconto_percentual' => 10,
            'desconto_valor' => 0,
            'acrescimo_valor' => 10,
            'data_base_precos' => Carbon::parse('2024-01-15'),
        ]);

        $local = OrcamentoLocal::create([
            'orcamento_id' => $orcamento->id,
            'nome' => 'Cozinha',
            'ordem' => 1,
        ]);

        $peca = OrcamentoPeca::create([
            'orcamento_local_id' => $local->id,
            'material_id' => $material->id,
            'material_price_id' => $materialPrice->id,
            'identificador' => 'Bancada Principal',
            'largura_mm' => 600,
            'comprimento_mm' => 2000,
            'espessura_mm' => 20,
            'quantidade' => 1,
            'area_m2' => 1.200,
            'perimetro_ml' => 5.200,
            'preco_material_unitario' => 100,
            'preco_material_total' => 120,
            'preco_servico_total' => 0,
            'custos_extras' => 30,
            'total' => 0,
        ]);

        OrcamentoPecaServico::create([
            'orcamento_peca_id' => $peca->id,
            'servico_id' => $servico->id,
            'servico_price_id' => $servicoPrice->id,
            'descricao' => $servico->nome,
            'tipo_cobranca' => $servico->tipo_cobranca,
            'quantidade' => 1,
            'preco_unitario' => 50,
            'total' => 50,
        ]);

        $calculator = new OrcamentoCalculator();
        $calculator->recalcular($orcamento);

        $orcamento->refresh();
        $peca->refresh();
        $local->refresh();

        $this->assertEquals(50.00, (float) $peca->preco_servico_total);
        $this->assertEquals(200.00, (float) $peca->total);

        $this->assertEquals(120.00, (float) $local->total_material);
        $this->assertEquals(50.00, (float) $local->total_servico);
        $this->assertEquals(30.00, (float) $local->total_custos_extras);
        $this->assertEquals(200.00, (float) $local->total);

        $this->assertEquals(120.00, (float) $orcamento->total_material);
        $this->assertEquals(50.00, (float) $orcamento->total_servico);
        $this->assertEquals(30.00, (float) $orcamento->total_custos_extras);
        $this->assertEquals(200.00, (float) $orcamento->total_bruto);
        $this->assertEquals(20.00, (float) $orcamento->total_desconto);
        $this->assertEquals(20.00, (float) $orcamento->desconto_valor);
        $this->assertEquals(10.00, (float) $orcamento->total_acrescimo);
        $this->assertEquals(190.00, (float) $orcamento->total_liquido);
    }
}
