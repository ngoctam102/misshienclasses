<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use Illuminate\Http\Request;

class HighlightController extends Controller
{
    public function index()
    {
        return view('highlights.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'start' => 'required|integer',
            'end' => 'required|integer',
            'passage_id' => 'required|exists:passages,id',
        ]);

        $highlight = Highlight::create($validated);

        return response()->json($highlight);
    }

    public function destroy(Highlight $highlight)
    {
        $highlight->delete();
        return response()->json(['message' => 'Highlight deleted successfully']);
    }
}
