<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WarrantyClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarrantyClaimController extends Controller
{
    /**
     * GET /warranty-claims
     * قائمة مطالبات الضمان (paginated)
     */
    public function index(Request $request)
    {
        $claims = WarrantyClaim::paginate(15);

        return response()->json($claims);
    }

    /**
     * POST /warranty-claims
     * فتح مطالبة ضمان جديدة
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
            'issue_description' => 'required|string',
            'claim_type' => 'required|in:quality_warranty,accidental_damage',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $claim = WarrantyClaim::create([
            'job_id' => $request->job_id,
            'issue_description' => $request->issue_description,
            'claim_type' => $request->claim_type,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'تم تسجيل مطالبة الضمان بنجاح',
            'claim' => $claim,
        ]);
    }

    /**
     * GET /warranty-claims/{claim}
     * تفاصيل مطالبة ضمان واحدة
     */
    public function show(WarrantyClaim $claim)
    {
        return response()->json($claim);
    }
}
