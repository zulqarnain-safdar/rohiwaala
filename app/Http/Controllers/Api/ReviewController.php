<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Job;
use App\Models\Farmer;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Review::with(['job.order', 'farmer', 'technician']);

        if ($user->role === 'farmer') {
            $farmer = Farmer::where('user_id', $user->id)->first();
            if ($farmer) {
                $query->where('farmer_id', $farmer->id);
            }
        } elseif ($user->role === 'technician') {
            $technician = Technician::where('user_id', $user->id)->first();
            if ($technician) {
                $query->where('technician_id', $technician->id);
            }
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'reviews' => $reviews
        ]);
    }

    public function show(Request $request, Review $review)
    {
        $user = $request->user();
        
        // Check authorization
        if ($user->role === 'farmer') {
            $farmer = Farmer::where('user_id', $user->id)->first();
            if (!$farmer || $review->farmer_id !== $farmer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        } elseif ($user->role === 'technician') {
            $technician = Technician::where('user_id', $user->id)->first();
            if (!$technician || $review->technician_id !== $technician->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        }

        $review->load(['job.order', 'farmer', 'technician']);

        return response()->json([
            'success' => true,
            'review' => $review
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:technician_jobs,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'rating_breakdown' => 'nullable|array',
            'rating_breakdown.service_quality' => 'nullable|integer|min:1|max:5',
            'rating_breakdown.punctuality' => 'nullable|integer|min:1|max:5',
            'rating_breakdown.professionalism' => 'nullable|integer|min:1|max:5',
            'rating_breakdown.communication' => 'nullable|integer|min:1|max:5',
            'rating_breakdown.equipment_quality' => 'nullable|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        if ($user->role !== 'farmer') {
            return response()->json([
                'success' => false,
                'message' => 'Only farmers can create reviews'
            ], 403);
        }

        $farmer = Farmer::where('user_id', $user->id)->first();
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $job = Job::with(['order', 'technician'])->find($request->job_id);
        if (!$job || $job->order->farmer_id !== $farmer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        if ($job->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Can only review completed jobs'
            ], 400);
        }

        // Check if review already exists
        $existingReview = Review::where('job_id', $request->job_id)
            ->where('farmer_id', $farmer->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Review already exists for this job'
            ], 400);
        }

        $review = Review::create([
            'job_id' => $request->job_id,
            'farmer_id' => $farmer->id,
            'technician_id' => $job->technician_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'rating_breakdown' => $request->rating_breakdown,
            'is_verified' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review created successfully',
            'review' => $review->load(['job.order', 'farmer', 'technician'])
        ], 201);
    }

    public function update(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'rating_breakdown' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        if ($user->role !== 'farmer') {
            return response()->json([
                'success' => false,
                'message' => 'Only farmers can update reviews'
            ], 403);
        }

        $farmer = Farmer::where('user_id', $user->id)->first();
        if (!$farmer || $review->farmer_id !== $farmer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'rating_breakdown' => $request->rating_breakdown,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'review' => $review->fresh()
        ]);
    }

    public function destroy(Request $request, Review $review)
    {
        $user = $request->user();
        if ($user->role !== 'farmer') {
            return response()->json([
                'success' => false,
                'message' => 'Only farmers can delete reviews'
            ], 403);
        }

        $farmer = Farmer::where('user_id', $user->id)->first();
        if (!$farmer || $review->farmer_id !== $farmer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }

    public function technicianStats(Request $request, Technician $technician)
    {
        $reviews = Review::where('technician_id', $technician->id)->get();
        
        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating');
        
        $ratingBreakdown = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        $categoryAverages = [];
        if ($totalReviews > 0) {
            $categoryAverages = [
                'service_quality' => $reviews->avg('rating_breakdown.service_quality'),
                'punctuality' => $reviews->avg('rating_breakdown.punctuality'),
                'professionalism' => $reviews->avg('rating_breakdown.professionalism'),
                'communication' => $reviews->avg('rating_breakdown.communication'),
                'equipment_quality' => $reviews->avg('rating_breakdown.equipment_quality'),
            ];
        }

        return response()->json([
            'success' => true,
            'stats' => [
                'total_reviews' => $totalReviews,
                'average_rating' => round($averageRating, 2),
                'rating_breakdown' => $ratingBreakdown,
                'category_averages' => $categoryAverages,
            ]
        ]);
    }
}