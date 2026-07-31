<?php

namespace App\Events;

use App\Models\CraftsmanProfile;
use App\Models\Job;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * ده الـ Event اللي هينده عليه عمود الاء (Matching/Contracts) لما شغلانة تخلص
 * بالـ OTP وييتأكد العميل. إسراء بتسمعله عشان تحدّث verification_tier
 * وتزود jobs_completed_count.
 */
class JobCompletedEvent
{
    use Dispatchable;

    public function __construct(
        public CraftsmanProfile $craftsmanProfile,
        public ?Job $job = null
    ) {
    }
}

