<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Passage;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    protected $model = Question::class;
    protected static $questionNumber = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'test_id' => Test::factory(),
            'passage_id' => Passage::factory(),
            'type' => Question::TYPE_MULTIPLE_CHOICE,
            'instruction' => 'Choose the correct letter, A, B, C, D, E or F',
            'question_text' => fake()->sentence() . '?',
            'options' => $this->generateOptions(),
            'correct_answer' => ['A'],
            'explanation' => fake()->paragraph(),
            'score' => 1,
            'order' => static::$questionNumber++
        ];
    }

    protected function generateOptions()
    {
        $options = [];
        $letters = range('A', 'F');
        foreach ($letters as $letter) {
            $options[] = ['text' => $letter . '. ' . fake()->sentence()];
        }
        return $options;
    }

    public function fillInBlank()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Question::TYPE_FILL_IN_BLANK,
                'instruction' => 'Complete the sentences below. Write NO MORE THAN TWO WORDS from the passage for each answer.',
                'question_text' => fake()->sentence() . ' ___ ' . fake()->sentence(),
                'options' => null,
                'correct_answer' => [fake()->word()],
            ];
        });
    }

    public function trueFalseNotGiven()
    {
        return $this->state(function (array $attributes) {
            $answers = ['True', 'False', 'Not Given'];
            return [
                'type' => Question::TYPE_TRUE_FALSE_NOT_GIVEN,
                'instruction' => 'Do the following statements agree with the information in the reading text?',
                'question_text' => fake()->sentence() . '?',
                'options' => [
                    ['text' => 'True'],
                    ['text' => 'False'],
                    ['text' => 'Not Given']
                ],
                'correct_answer' => [fake()->randomElement($answers)],
            ];
        });
    }

    public function matchingHeading()
    {
        return $this->state(function (array $attributes) {
            $pairs = [];
            $letters = range('A', 'H');
            foreach ($letters as $letter) {
                $pairs[] = [
                    'left' => 'Heading ' . $letter,
                    'right' => 'Paragraph ' . $letter
                ];
            }
            return [
                'type' => Question::TYPE_MATCHING_HEADING,
                'instruction' => 'Match the headings with the paragraphs. Write the correct letter, A-H, in boxes on your answer sheet.',
                'question_text' => 'Match the following headings with the paragraphs A-H',
                'matching_pairs' => $pairs,
                'correct_answer' => $pairs,
            ];
        });
    }

    public function matchingParagraph()
    {
        return $this->state(function (array $attributes) {
            $pairs = [];
            $letters = range('A', 'H');
            foreach ($letters as $letter) {
                $pairs[] = [
                    'left' => 'Paragraph ' . $letter,
                    'right' => 'Information ' . $letter
                ];
            }
            return [
                'type' => Question::TYPE_MATCHING_PARAGRAPH,
                'instruction' => 'Match the following information with the paragraphs A-H.',
                'question_text' => 'Match the following statements with the paragraphs A-H',
                'matching_pairs' => $pairs,
                'correct_answer' => $pairs,
            ];
        });
    }

    public function resetQuestionNumber()
    {
        static::$questionNumber = 1;
        return $this;
    }
}
