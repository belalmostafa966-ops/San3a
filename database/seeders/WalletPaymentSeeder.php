<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\CommissionRule;

class WalletPaymentSeeder extends Seeder
{
    /**
     * TODO: كل القيم هنا placeholders مقترحة من البيزنس (جون)، لسه مش نهائية.
     * لازم تتراجع وتتأكد قبل الإطلاق الفعلي (production).
     */
    public function run(): void
    {
        // TODO: الأسعار دي مقترحة من جون في اجتماع الفريق - محتاجة تأكيد نهائي
        SubscriptionPlan::create([
            'name' => 'Basic',
            'price' => 400,
            'monthly_leads_or_requests' => 6,
            'perks_json' => json_encode(['support' => 'standard']),
        ]);

        SubscriptionPlan::create([
            'name' => 'Advanced',
            'price' => 700,
            'monthly_leads_or_requests' => 15,
            'perks_json' => json_encode(['support' => 'priority']),
        ]);

        SubscriptionPlan::create([
            'name' => 'Premium',
            'price' => 1000,
            'monthly_leads_or_requests' => null, // null = عدد غير محدود
            'perks_json' => json_encode(['support' => 'vip', 'unlimited_requests' => true]),
        ]);

        // TODO: النسبة دي (10-15%) مقترحة من البيزنس، لسه مش نهائية
        CommissionRule::create([
            'profession_id' => null, // قاعدة عامة تطبق على كل المهن
            'min_percent' => 10.00,
            'max_percent' => 15.00,
        ]);
    }
}