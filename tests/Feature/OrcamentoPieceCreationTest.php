<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Material;
use App\Models\MaterialPrice;
use App\Models\Orcamento;
use App\Models\OrcamentoLocal;
use App\Models\Servico;
use App\Models\ServicoPrice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OrcamentoPieceCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_criacao_de_peca_utiliza_preco_vigente_na_data_do_orcamento()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cliente = Cliente::factory()->create();

        $material = Material::factory()->create();
        $oldPrice = MaterialPrice::create([
            'material_id' => $material->id,
            'data_inicio' => Carbon::parse('2024-01-01'),
            'preco_m2' => 200,
            'moeda' => 'BRL',
        ]);
        MaterialPrice::create([
            'material_id' => $material->id,
            'data_inicio' => Carbon::parse('2024-03-01'),
            'preco_m2' => 250,
            'moeda' => 'BRL',
        ]);

        $servico = Servico::factory()->create();
        ServicoPrice::create([
            'servico_id' => $servico->id,
            'data_inicio' => Carbon::parse('2024-01-01'),
            'preco' => 40,
            'moeda' => 'BRL',
        ]);

        $orcamento = Orcamento::create([
            'cliente_id' => $cliente->id,
            'payment_method_id' => null,
            'data' => Carbon::parse('2024-02-10'),
            'status' => 'rascunho',
            'desconto_tipo' => 'nenhum',
            'data_base_precos' => Carbon::parse('2024-02-10'),
        ]);

        $local = OrcamentoLocal::create([
            'orcamento_id' => $orcamento->id,
            'nome' => 'Banheiro',
            'ordem' => 1,
        ]);

        $response = $this->post(route('orcamentos.locais.pecas.store', [$orcamento, $local]), [
            'material_id' => $material->id,
            'identificador' => 'Balcão da pia',
            'largura_mm' => 500,
            'comprimento_mm' => 1800,
            'quantidade' => 1,
        ]);

        $response->assertRedirect();

        $peca = $local->fresh('pecas')->pecas->first();

        $this->assertNotNull($peca);
        $this->assertEquals($oldPrice->id, $peca->material_price_id);
        $this->assertEquals(200.00, (float) $peca->preco_material_unitario);
    }
}
