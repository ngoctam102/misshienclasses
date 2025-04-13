<?php

namespace Database\Factories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Test>
 */
class TestFactory extends Factory
{
    protected $model = Test::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement([Test::TYPE_LISTENING, Test::TYPE_READING]),
            'time_limit' => fake()->randomElement([30, 45, 60]),
            'is_active' => fake()->boolean(80), // 80% chance of being active
            'published_at' => fake()->randomElement([
                null,
                fake()->dateTimeBetween('-1 month', '+1 month')
            ]),
        ];
    }

    public function listening()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Test::TYPE_LISTENING,
                'time_limit' => 30, // Listening tests thường ngắn hơn
            ];
        });
    }

    public function reading()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Test::TYPE_READING,
                'time_limit' => 60, // Reading tests thường dài hơn
            ];
        });
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
                'is_active' => true,
            ];
        });
    }

    public function draft()
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => null,
            ];
        });
    }
}
