<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\TestAttempt;
use App\Models\TextHighlight;
use App\Models\Highlight;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_approved',
        'approved_at',
        'approved_by',
        'is_online'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'is_online' => 'boolean'
    ];

    // Relationships
    public function testAttempts()
    {
        return $this->hasMany(TestAttempt::class);
    }

    public function highlights()
    {
        return $this->hasMany(Highlight::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approvedUsers()
    {
        return $this->hasMany(User::class, 'approved_by');
    }

    // Helper methods
    public function getTestProgress($testId)
    {
        $test = Test::findOrFail($testId);
        $totalQuestions = $test->getTotalQuestions();

        if ($totalQuestions === 0) {
            return 0;
        }

        $answeredQuestions = $this->testAttempts()
            ->where('test_id', $testId)
            ->count();

        return ($answeredQuestions / $totalQuestions) * 100;
    }

    public function getTestScore($testId)
    {
        return $this->testAttempts()
            ->where('test_id', $testId)
            ->avg('score') ?? 0;
    }

    public function getRecentTests($limit = 5)
    {
        return $this->testAttempts()
            ->select('test_id')
            ->distinct()
            ->with('test')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->pluck('test');
    }

    public function getHighlightsByPassage($passageId)
    {
        return $this->highlights()
            ->where('passage_id', $passageId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Approval methods
    public function approve(User $approver)
    {
        return $this->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => $approver->id
        ]);
    }

    public function reject()
    {
        return $this->update([
            'is_approved' => false,
            'approved_at' => null,
            'approved_by' => null
        ]);
    }

    public function isApproved(): bool
    {
        return $this->is_approved === true && $this->approved_at !== null;
    }

    public function isPendingApproval(): bool
    {
        return $this->is_approved === false || $this->approved_at === null;
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }
}
