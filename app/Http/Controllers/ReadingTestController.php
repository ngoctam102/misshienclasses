<?php

namespace App\Http\Controllers;

use App\Models\MatchingOption;
use App\Models\MatchingQuestion;
use App\Models\Passage;
use App\Models\Question;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;
use App\Models\Test;
use App\Models\Result;
use Illuminate\Http\Request;

class ReadingTestController extends Controller
{
    public function readingTestHandle($test_slug)
    {
        $test = Test::where('slug', $test_slug)->first();

        $passages = Passage::where('test_id', $test->id)
            ->orderBy('order', 'asc')
            ->get();

        $questionGroups = QuestionGroup::whereIn('passage_id', $passages->pluck('id'))
            ->orderBy('order', 'asc')
            ->get();

        $questions = Question::whereIn('group_id', $questionGroups->pluck('id'))
            ->orderBy('order', 'asc')
            ->get();

        // Lấy các đáp án cho dạng Multiple Choice, Correct Answer
        $questionOptions = QuestionOption::whereIn('question_id', $questions->pluck('id'))->get();

        // Lấy danh sách Matching Questions
        $matchingQuestions = MatchingQuestion::whereIn('question_id', $questions->pluck('id'))->get();

        // Lấy danh sách các headings cho dạng Matching
        $matchingOptions = MatchingOption::whereIn('group_id', $questionGroups->pluck('id'))->get();

        return view('reading-test-handle', compact('test', 'passages', 'questionGroups', 'questions', 'questionOptions', 'matchingQuestions', 'matchingOptions'));
    }


    private function calculateBandScore($correctAnswers)
    {
        if ($correctAnswers >= 39) return 9.0;
        if ($correctAnswers >= 37) return 8.5;
        if ($correctAnswers >= 35) return 8.0;
        if ($correctAnswers >= 33) return 7.5;
        if ($correctAnswers >= 30) return 7.0;
        if ($correctAnswers >= 27) return 6.5;
        if ($correctAnswers >= 23) return 6.0;
        if ($correctAnswers >= 20) return 5.5;
        if ($correctAnswers >= 16) return 5.0;
        if ($correctAnswers >= 13) return 4.5;
        if ($correctAnswers >= 10) return 4.0;
        if ($correctAnswers >= 7) return 3.5;
        if ($correctAnswers >= 5) return 3.0;
        if ($correctAnswers >= 3) return 2.5;
        return 0;
    }

    public function submit(Request $request, $slug)
    {
        // Lấy bài test
        $test = Test::where('slug', $slug)->firstOrFail();

        // Lấy tất cả câu hỏi của bài test
        $questions = Question::where('test_id', $test->id)
            ->orderBy('order', 'asc')
            ->get();

        // Lấy câu trả lời của người dùng
        $userAnswers = $request->input('answer', []);
        // dd($userAnswers[5]);

        // Tính điểm
        $correctAnswers = 0;
        $totalQuestions = $test->total_questions;

        $results = [];

        foreach ($questions as $question) {
            $userAnswer = $userAnswers[$question->id] ?? null;
            $isCorrect = false;

            // Kiểm tra câu trả lời đúng
            if ($question->question_type == 'matching' || $question->question_type == 'correct_answer' || $question->question_type == 'fill_in_blank_with_options' || $question->question_type == 'true_false_not_given') {
                // Đối với dạng matching, so sánh id của option được chọn với matching_answer
                if ($userAnswer == $question->answers()->first()->correct_answer) {
                    $correctAnswers++;
                    $isCorrect = true;
                }
            } elseif ($question->question_type == 'fill_in_blank') {
                // Đối với dạng fill in blank, so sánh chuỗi
                if (strcasecmp(trim($userAnswer), trim($question->answers()->first()->correct_answer)) == 0) {
                    $correctAnswers++;
                    $isCorrect = true;
                }
            } else {
                // Đối với các dạng multiple choice
                $answers = $question->answers()->get();
                foreach ($answers as $answer) {
                    if ($userAnswer == $answer->id) {
                        $correctAnswers++;
                        $isCorrect = true;
                        break;
                    }
                }
            }

            $results[] = [
                'question' => $question,
                'userAnswer' => $userAnswer,
                'isCorrect' => $isCorrect,
                'explanation' => $question->explanation ?? '',
                'correctAnswer' => $this->getCorrectAnswerText($question, $question->answers()->first()->correct_answer)
            ];
        }

        // Sắp xếp lại mảng results theo order của câu hỏi
        usort($results, function ($a, $b) {
            return $a['question']->order - $b['question']->order;
        });

        // Tính điểm band
        $bandScore = $this->calculateBandScore($correctAnswers);

        // Lưu kết quả
        $result = Result::create([
            'user_id' => auth()->id(),
            'test_id' => $test->id,
            'duration' => $test->duration,
            'correct_answers' => $correctAnswers,
            'score' => $bandScore,
            'is_submitted' => true
        ]);

        // Trả về view hiển thị kết quả
        return view('reading-test.result', [
            'test' => $test,
            'results' => $results,
            'bandScore' => $bandScore,
        ]);
    }

    private function getCorrectAnswerText($question, $correctAnswerId)
    {
        switch ($question->question_type) {
            case 'true_false_not_given':
            case 'fill_in_blank':
                return $correctAnswerId;

            case 'matching':
                $matchingOption = MatchingOption::find($correctAnswerId);
                return $matchingOption ? $matchingOption->option_text : '';

            case 'correct_answer':
            case 'fill_in_blank_with_options':
                $questionOption = QuestionOption::find($correctAnswerId);
                return $questionOption ? $questionOption->option_text : '';

            case 'multiple_choice':
                $correctAnswers = json_decode($correctAnswerId, true);
                $options = QuestionOption::whereIn('id', $correctAnswers)->pluck('option_text')->toArray();
                return implode(', ', $options);

            default:
                return '';
        }
    }
}
