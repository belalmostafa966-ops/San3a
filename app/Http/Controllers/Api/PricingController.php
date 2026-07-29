<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PricingRule;
use App\Models\StandardizedServicePrice;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * GET /pricing/rules
     * عام (بدون تسجيل دخول) — فلتر اختياري بـ profession_id
     */
    public function rules(Request $request)
    {
        $query = PricingRule::with('profession');

        if ($request->has('profession_id')) {
            $query->where('profession_id', $request->profession_id);
        }

        return response()->json($query->get());
    }

    /**
     * GET /pricing/standardized-services
     * عام (بدون تسجيل دخول) — فلتر اختياري بـ profession_id
     */
    public function standardizedServices(Request $request)
    {
        $query = StandardizedServicePrice::with('profession');

        if ($request->has('profession_id')) {
            $query->where('profession_id', $request->profession_id);
        }

        return response()->json($query->get());
    }
}
