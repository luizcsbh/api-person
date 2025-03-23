<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cidade>
 */
class CidadeFactory extends Factory
{
    protected $model = \App\Models\Cidade::class;

    public function definition(): array
    {
        return [
            'cid_nome' => $this->faker->city(),
            'cid_uf' => $this->faker->stateAbbr(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
