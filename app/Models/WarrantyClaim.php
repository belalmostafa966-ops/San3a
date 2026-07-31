<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'issue_description',
        'claim_type',
        'status',
        'resolution',
        'insurance_ref',
    ];

    protected $casts = [
        'job_id' => 'integer',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}

