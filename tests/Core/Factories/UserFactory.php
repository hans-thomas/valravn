<?php

namespace Hans\Valravn\Tests\Core\Factories;

use Hans\Valravn\Tests\Core\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'     => fake()->name(),
            'email'    => fake()->email(),
            'password' => bcrypt('password'),
        ];
    }
}
