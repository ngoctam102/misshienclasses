<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class Test extends Model
{
    use HasFactory, SoftDeletes, HasEvents;

    protected $fillable = [
        'title',
        'test_type',
        'duration',
        'total_questions',
        'is_published',
        'published_at',
        'description',
        'slug'
    ];

    protected static function booted()
    {
        static::creating(function ($test) {
            $test->slug = Str::slug($test->title);
        });
    }

    public function passages(): HasMany
    {
        return $this->hasMany(Passage::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function isReading(): bool
    {
        return $this->test_type === 'reading';
    }

    public function isListening(): bool
    {
        return $this->test_type === 'listening';
    }

    public function audioFiles(): HasMany
    {
        return $this->hasMany(AudioFile::class);
    }

    public function questionGroups(): HasMany
    {
        return $this->hasMany(QuestionGroup::class);
    }
}
