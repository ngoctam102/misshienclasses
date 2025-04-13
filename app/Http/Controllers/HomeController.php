<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // $recentTests = auth()->user()->testAttempts()
        //     ->select('test_id', 'created_at')
        //     ->with(['test' => function ($query) {
        //         $query->withCount('questions');
        //     }])
        //     ->distinct('test_id')
        //     ->orderBy('created_at', 'desc')
        //     ->take(8)
        //     ->get()
        //     ->map(function ($attempt) {
        //         return [
        //             'test' => $attempt->test,
        //             'created_at' => $attempt->created_at,
        //             'correct_answers' => $attempt->where('test_id', $attempt->test_id)
        //                 ->where('is_correct', true)
        //                 ->count(),
        //             'total_questions' => $attempt->test->questions_count,
        //             'score' => $attempt->where('test_id', $attempt->test_id)
        //                 ->avg('score'),
        //             'time_taken' => $attempt->where('test_id', $attempt->test_id)
        //                 ->sum('time_taken')
        //         ];
        //     });

        return view('home');
    }

    public function readingTest()
    {
        $tests = Test::where('is_published', true)
            ->where('test_type', 'reading')
            ->get();
        return view('reading-test', compact('tests'));
    }

    public function listeningTest()
    {
        return view('listening-test');
    }
}
