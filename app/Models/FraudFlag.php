<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudFlag extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'flag_type', 'score', 'status'];

    protected $casts = [
        'user_id' => 'integer',
        'score' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

