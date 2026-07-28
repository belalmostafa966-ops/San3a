<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'rated_by',
        'rated_user_id',
        'direction',
        'score',
        'behavior_score',
        'comment',
    ];

    protected $casts = [
        'score' => 'integer',
        'behavior_score' => 'integer',
    ];

    // الشغلانة اللي التقييم ده بتاعها
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    // اليوزر اللي بعت التقييم
    public function ratedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_by');
    }

    // اليوزر اللي اتقيّم
    public function ratedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }
}
