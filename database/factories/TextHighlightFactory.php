<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Passage;
use App\Models\TextHighlight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TextHighlight>
 */
class TextHighlightFactory extends Factory
{
    protected $model = TextHighlight::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $passage = Passage::factory()->create();
        $content = $passage->content;

        // Tạo một highlight ngẫu nhiên trong nội dung
        $contentLength = strlen($content);
        $startOffset = fake()->numberBetween(0, $contentLength - 50);
        $length = fake()->numberBetween(10, 50);
        $endOffset = min($startOffset + $length, $contentLength);

        return [
            'user_id' => User::factory(),
            'passage_id' => $passage->id,
            'start_offset' => $startOffset,
            'end_offset' => $endOffset,
            'highlighted_text' => substr($content, $startOffset, $endOffset - $startOffset),
            'note' => fake()->boolean(30) ? fake()->sentence() : null, // 30% chance có note
            'color' => fake()->randomElement(['yellow', 'green', 'blue', 'pink']),
        ];
    }

    public function withNote()
    {
        return $this->state(function (array $attributes) {
            return [
                'note' => fake()->paragraph(),
            ];
        });
    }

    public function withColor($color)
    {
        return $this->state(function (array $attributes) use ($color) {
            return [
                'color' => $color,
            ];
        });
    }
}
