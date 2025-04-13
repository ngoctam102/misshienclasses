<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'passage_id',
        'group_id',
        'question_type',
        'question_content',
        'order',
        'explanation'
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(QuestionGroup::class, 'group_id');
    }

    public function passage(): BelongsTo
    {
        return $this->belongsTo(Passage::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    public function matchingQuestions(): HasMany
    {
        return $this->hasMany(MatchingQuestion::class);
    }
}
