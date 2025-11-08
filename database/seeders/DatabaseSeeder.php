<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Material;
use App\Models\MaterialPrice;
use App\Models\PaymentMethod;
use App\Models\Servico;
use App\Models\ServicoPrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);

        $clientes = Cliente::factory(10)->create();

        $inicioVigencia = Carbon::now()->startOfMonth();

        $materiais = Material::factory(6)->create();
        foreach ($materiais as $material) {
            MaterialPrice::create([
                'material_id' => $material->id,
                'data_inicio' => $inicioVigencia->copy()->subMonths(2),
                'data_fim' => $inicioVigencia->copy()->subMonth(),
                'preco_m2' => fake()->randomFloat(2, 250, 550),
                'moeda' => 'BRL',
                'ativo' => true,
                'observacao' => 'Tabela anterior',
            ]);

            MaterialPrice::create([
                'material_id' => $material->id,
                'data_inicio' => $inicioVigencia,
                'preco_m2' => fake()->randomFloat(2, 280, 600),
                'moeda' => 'BRL',
                'ativo' => true,
                'observacao' => 'Tabela vigente',
            ]);
        }

        $servicos = Servico::factory(5)->create();
        foreach ($servicos as $servico) {
            ServicoPrice::create([
                'servico_id' => $servico->id,
                'data_inicio' => $inicioVigencia->copy()->subMonths(3),
                'data_fim' => $inicioVigencia->copy()->subMonth(),
                'preco' => fake()->randomFloat(2, 35, 120),
                'moeda' => 'BRL',
                'observacao' => 'Valor anterior',
            ]);

            ServicoPrice::create([
                'servico_id' => $servico->id,
                'data_inicio' => $inicioVigencia,
                'preco' => fake()->randomFloat(2, 45, 150),
                'moeda' => 'BRL',
                'observacao' => 'Valor vigente',
            ]);
        }

        PaymentMethod::insert([
            [
                'codigo' => 'avista',
                'nome' => 'Dinheiro / Transferência à vista',
                'permite_parcelamento' => false,
                'max_parcelas' => 1,
                'taxa_percentual' => 0,
                'descricao' => 'Pagamento à vista com 5% de desconto sugerido.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'pix',
                'nome' => 'PIX',
                'permite_parcelamento' => false,
                'max_parcelas' => 1,
                'taxa_percentual' => 0,
                'descricao' => 'Pagamento instantâneo via PIX.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'cartao',
                'nome' => 'Cartão de crédito',
                'permite_parcelamento' => true,
                'max_parcelas' => 6,
                'taxa_percentual' => 2.9,
                'descricao' => 'Parcelamento em até 6x com taxa administrativa.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
