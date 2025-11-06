<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question_stem' => $this->faker->sentence(),
            'topic' => $this->faker->randomElement(['Math', 'Science', 'History']),
            'difficulty_level' => $this->faker->numberBetween(1, 5),
            'option_a' => $this->faker->word(),
            'option_b' => $this->faker->word(),
            'option_c' => $this->faker->word(),
            'option_d' => $this->faker->word(),
            'correct_option' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'explanation' => $this->faker->sentence(),
        ];
    }
}
