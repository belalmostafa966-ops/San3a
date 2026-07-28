<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    // عرض كل الباقات المتاحة
    public function plans()
    {
        return response()->json(SubscriptionPlan::all());
    }

    // عرض اشتراك الحرفي الحالي
    public function current(Request $request)
    {
        $subscription = Subscription::where('craftsman_id', $request->user()->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'لا يوجد اشتراك فعّال'], 404);
        }

        return response()->json($subscription->load('plan'));
    }

    // الاشتراك في باقة جديدة
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $wallet = $request->user()->wallet;

        // التأكد إن الرصيد كافي
        if ($wallet->availableBalance() < $plan->price) {
            return response()->json(['message' => 'الرصيد غير كافٍ للاشتراك في هذه الباقة'], 400);
        }

        // إلغاء أي اشتراك سابق فعّال
        Subscription::where('craftsman_id', $request->user()->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        // خصم قيمة الاشتراك من المحفظة مباشرة
        $wallet->balance -= $plan->price;
        $wallet->save();

        $wallet->transactions()->create([
            'type' => 'withdrawal',
            'amount' => $plan->price,
            'balance_after' => $wallet->balance,
            'description' => 'اشتراك في باقة ' . $plan->name,
        ]);

        // إنشاء الاشتراك الجديد
        $subscription = Subscription::create([
            'craftsman_id' => $request->user()->id,
            'plan_id' => $plan->id,
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addMonth(),
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'تم الاشتراك بنجاح',
            'subscription' => $subscription->load('plan'),
        ]);
    }

    // إلغاء الاشتراك الحالي
    public function cancel(Request $request)
    {
        $subscription = Subscription::where('craftsman_id', $request->user()->id)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'لا يوجد اشتراك فعّال لإلغائه'], 404);
        }

        $subscription->update(['status' => 'cancelled']);

        return response()->json(['message' => 'تم إلغاء الاشتراك']);
    }
}