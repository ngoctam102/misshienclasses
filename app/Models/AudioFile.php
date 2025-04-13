<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AudioFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'title',
        'transcript',
        'audio_url',
        'order'
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function questionGroups(): HasMany
    {
        return $this->hasMany(QuestionGroup::class);
    }
}
