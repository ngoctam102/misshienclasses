<?php

namespace Database\Factories;

use App\Models\Test;
use App\Models\User;
use App\Models\Question;
use App\Models\Passage;
use App\Models\TestAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestAttempt>
 */
class TestAttemptFactory extends Factory
{
    protected $model = TestAttempt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $question = Question::factory()->create();
        $isCorrect = fake()->boolean(70); // 70% chance of correct answer

        return [
            'user_id' => User::factory(),
            'test_id' => $question->passage->test_id,
            'passage_id' => $question->passage_id,
            'question_id' => $question->id,
            'user_answer' => $isCorrect ? $question->correct_answer : $this->generateWrongAnswer($question),
            'is_correct' => $isCorrect,
            'score' => $isCorrect ? 1 : 0,
            'time_taken' => fake()->numberBetween(10, 120), // 10 giây đến 2 phút mỗi câu
        ];
    }

    protected function generateWrongAnswer($question)
    {
        if ($question->question_type === Question::TYPE_MULTIPLE_CHOICE) {
            $options = array_keys($question->options);
            $options = array_filter($options, fn($opt) => $opt !== $question->correct_answer);
            return fake()->randomElement($options);
        }

        if ($question->question_type === Question::TYPE_TRUE_FALSE) {
            return $question->correct_answer === 'True' ? 'False' : 'True';
        }

        if ($question->question_type === Question::TYPE_FILL_IN_BLANK) {
            return fake()->word(); // Random wrong word
        }

        if ($question->question_type === Question::TYPE_MATCHING) {
            $correctAnswers = explode(',', $question->correct_answer);
            shuffle($correctAnswers); // Đảo ngẫu nhiên để tạo câu trả lời sai
            return implode(',', $correctAnswers);
        }

        return '';
    }

    public function correct()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_answer' => Question::find($attributes['question_id'])->correct_answer,
                'is_correct' => true,
                'score' => 1,
            ];
        });
    }

    public function incorrect()
    {
        return $this->state(function (array $attributes) {
            $question = Question::find($attributes['question_id']);
            return [
                'user_answer' => $this->generateWrongAnswer($question),
                'is_correct' => false,
                'score' => 0,
            ];
        });
    }
}
