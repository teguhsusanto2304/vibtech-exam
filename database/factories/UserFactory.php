<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'company' => $this->faker->company(),
            'data_status' => $this->faker->randomElement(['active', 'inactive', 'suspended']),
            'attempts_used' => $this->faker->numberBetween(0, 10),
            'last_score' => $this->faker->optional()->randomFloat(2, 0, 100),
            'last_outcome' => $this->faker->optional()->randomElement(['Passed', 'Failed']),
            'last_attempt_date' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Default password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user’s email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
