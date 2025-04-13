<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'passage_id',
        'audio_file_id',
        'order',
        'description',
        'content'
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function passage(): BelongsTo
    {
        return $this->belongsTo(Passage::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function audioFile(): BelongsTo
    {
        return $this->belongsTo(AudioFile::class);
    }
}
