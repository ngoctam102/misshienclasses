<?php

namespace Database\Factories;

use App\Models\Test;
use App\Models\Passage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Passage>
 */
class PassageFactory extends Factory
{
    protected $model = Passage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'test_id' => Test::factory(),
            'part_number' => fake()->numberBetween(1, 4),
            'title' => fake()->sentence(4),
            'content' => fake()->paragraphs(3, true),
            'audio_path' => null,
        ];
    }

    public function withAudio()
    {
        return $this->state(function (array $attributes) {
            return [
                'audio_path' => 'audios/tests/' . fake()->uuid() . '.mp3',
            ];
        });
    }

    public function forListening()
    {
        return $this->state(function (array $attributes) {
            return [
                'test_id' => Test::factory()->listening(),
                'audio_path' => 'audios/tests/' . fake()->uuid() . '.mp3',
            ];
        });
    }

    public function forReading()
    {
        return $this->state(function (array $attributes) {
            return [
                'test_id' => Test::factory()->reading(),
                'content' => fake()->paragraphs(5, true), // Reading passages thường dài hơn
            ];
        });
    }
}
