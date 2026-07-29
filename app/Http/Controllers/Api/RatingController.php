<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * POST /ratings
     * تسجيل تقييم جديد (من عميل لصنايعي أو العكس)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
            'rated_user_id' => 'required|exists:users,id',
            'direction' => 'required|in:client_to_craftsman,craftsman_to_client',
            'score' => 'required|integer|min:1|max:5',
            'behavior_score' => 'nullable|integer',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rating = Rating::create([
            'job_id' => $request->job_id,
            'rated_by' => $request->user()->id,
            'rated_user_id' => $request->rated_user_id,
            'direction' => $request->direction,
            'score' => $request->score,
            'behavior_score' => $request->behavior_score,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'تم تسجيل التقييم بنجاح',
            'rating' => $rating,
        ]);
    }

    /**
     * GET /ratings/user/{userId}
     * متوسط تقييم يوزر معين + كل تقييماته
     */
    public function userRatings($userId)
    {
        $ratings = Rating::where('rated_user_id', $userId)->get();

        $averageScore = $ratings->avg('score');

        return response()->json([
            'average_score' => round($averageScore ?? 0, 2),
            'ratings' => $ratings,
        ]);
    }
}
