<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobProposal extends Model
{
    protected $fillable = [
        'job_request_id', 'craftsman_id', 'price_quote', 'message', 'status',
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function craftsman()
    {
        return $this->belongsTo(CraftsmanProfile::class, 'craftsman_id');
    }
}
