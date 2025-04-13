<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'passage_id',
        'group_id',
        'question_id',
        'option_text',
        'order',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function passage(): BelongsTo
    {
        return $this->belongsTo(Passage::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(QuestionGroup::class);
    }

    
}
