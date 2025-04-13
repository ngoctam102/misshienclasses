<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchingAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'group_id',
        'question_id',
        'matching_question_id',
        'matching_option_id',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(QuestionGroup::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function matchingQuestion(): BelongsTo
    {
        return $this->belongsTo(MatchingQuestion::class);
    }

    public function matchingOption(): BelongsTo
    {
        return $this->belongsTo(MatchingOption::class);
    }
}
