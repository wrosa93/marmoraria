<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        $tipo = $this->faker->randomElement(['pf', 'pj']);

        return [
            'nome' => $tipo === 'pf' ? $this->faker->name() : $this->faker->company(),
            'tipo_cliente' => $tipo,
            'documento' => $tipo === 'pf'
                ? $this->faker->unique()->numerify('###########')
                : $this->faker->unique()->numerify('##############'),
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->phoneNumber(),
            'telefone_secundario' => $this->faker->optional()->phoneNumber(),
            'endereco' => $this->faker->streetName(),
            'numero' => $this->faker->buildingNumber(),
            'bairro' => $this->faker->citySuffix(),
            'cidade' => $this->faker->city(),
            'estado' => $this->faker->stateAbbr(),
            'cep' => $this->faker->postcode(),
            'observacoes' => $this->faker->optional()->sentence(),
        ];
    }
}

