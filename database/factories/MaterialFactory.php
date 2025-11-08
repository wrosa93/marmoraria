<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Material>
 */
class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        $nome = $this->faker->randomElement([
            'Mármore Carrara',
            'Granito Preto São Gabriel',
            'Quartzo Branco Stellar',
            'Nanoglass Premium',
        ]);

        return [
            'codigo' => Str::slug($nome) . '-' . $this->faker->unique()->numberBetween(100, 999),
            'nome' => $nome,
            'tipo' => $this->faker->randomElement(['marmore', 'granito', 'quartzo']),
            'acabamento' => $this->faker->randomElement(['Polido', 'Escovado', 'Levigado']),
            'cor' => $this->faker->safeColorName(),
            'espessura_padrao_mm' => $this->faker->randomElement([20, 30]),
            'ativo' => true,
            'descricao' => $this->faker->optional()->paragraph(),
        ];
    }
}

