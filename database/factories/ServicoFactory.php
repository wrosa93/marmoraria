<?php

namespace Database\Factories;

use App\Models\Servico;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Servico>
 */
class ServicoFactory extends Factory
{
    protected $model = Servico::class;

    public function definition(): array
    {
        $nome = $this->faker->randomElement([
            'Instalação no local',
            'Furação para cooktop',
            'Acabamento de borda meia-esquadria',
            'Rebaixo para cuba',
        ]);

        return [
            'codigo' => Str::slug($nome) . '-' . $this->faker->unique()->numberBetween(10, 99),
            'nome' => $nome,
            'tipo_cobranca' => $this->faker->randomElement(['area', 'perimetro', 'peca']),
            'unidade_medida' => $this->faker->randomElement(['m2', 'ml', 'un']),
            'ativo' => true,
            'descricao' => $this->faker->optional()->sentence(),
        ];
    }
}

