<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blocks>
 */
class BlocksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->bs(),
            'field_1' => $this->faker->name(),
            'field_2' => $this->faker->company(),
            'field_3' => $this->faker->city(),
            'field_4' => $this->faker->streetAddress(),
        ];
    }
}
