<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passage extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'content',
        'order',
        'title'
    ];


    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(QuestionGroup::class);
    }

    
}
