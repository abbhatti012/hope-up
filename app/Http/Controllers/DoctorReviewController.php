<?php

namespace App\Http\Controllers;

use App\Models\DoctorReview;
use App\Models\User;
use App\Helpers\PaginationHelper;
use App\Enums\NotificationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorReviewController extends Controller
{
    /**
     * Display a listing of the reviews
     */
    public function index(Request $request)
    {
        $query = DoctorReview::with(['doctor', 'reviewer']);
        
        // Apply search filter
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('comments', 'like', '%' . $search . '%')
                  ->orWhere('rating_type', 'like', '%' . $search . '%');
            });
        }
        
        // Apply rating type filter
        if ($request->has('rating_type') && $request->rating_type) {
            $query->where('rating_type', $request->rating_type);
        }
        
        // Apply status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_approved', $request->status);
        }
        
        // Get per_page value from request or use default 10
        $perPage = $request->input('per_page', 50);
        $reviews = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        
        // For AJAX requests, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);
        }
        
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review
     */
    public function create()
    {
        $doctors = User::where('role', 'specialist')->get();
        $reviewers = User::where('role', 'user')->get();
        
        return view('admin.reviews.create', compact('doctors', 'reviewers'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'reviewer_id' => 'required|exists:users,id',
            'comments' => 'nullable|string|max:1000',
            'rating' => 'required|integer|between:1,5',
            'rating_type' => 'required|in:excellent,very_good,good,fair,poor,bad',
            'is_approved' => 'boolean'
        ]);

        // Check if doctor is actually a specialist
        $doctor = User::find($request->doctor_id);
        if (!$doctor || $doctor->role !== 'specialist') {
            return redirect()->back()->with([
                'notification' => 'Selected doctor is not a valid specialist.',
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        // Check if reviewer is actually a user (patient)
        $reviewer = User::find($request->reviewer_id);
        if (!$reviewer || $reviewer->role !== 'user') {
            return redirect()->back()->with([
                'notification' => 'Selected reviewer is not a valid patient.',
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        // Check if review already exists for this doctor-reviewer combination
        $existingReview = DoctorReview::where('doctor_id', $request->doctor_id)
            ->where('reviewer_id', $request->reviewer_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with([
                'notification' => 'A review already exists for this doctor by this reviewer.',
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        try {
            DoctorReview::create([
                'doctor_id' => $request->doctor_id,
                'reviewer_id' => $request->reviewer_id,
                'comments' => $request->comments,
                'rating' => $request->rating,
                'rating_type' => $request->rating_type,
                'is_approved' => $request->has('is_approved')
            ]);

            return redirect()->route('reviews.index')->with([
                'notification' => 'Review created successfully.',
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'notification' => 'An error occurred while creating the review: ' . $e->getMessage(),
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }
    }

    /**
     * Display the specified review
     */
    public function show(DoctorReview $review)
    {
        $review->load(['doctor', 'reviewer']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review
     */
    public function edit(DoctorReview $review)
    {
        $review->load(['doctor', 'reviewer']);
        $doctors = User::where('role', 'specialist')->get();
        $reviewers = User::where('role', 'user')->get();
        
        return view('admin.reviews.edit', compact('review', 'doctors', 'reviewers'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, DoctorReview $review)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'reviewer_id' => 'required|exists:users,id',
            'comments' => 'nullable|string|max:1000',
            'rating' => 'required|integer|between:1,5',
            'rating_type' => 'required|in:excellent,very_good,good,fair,poor,bad',
            'is_approved' => 'boolean'
        ]);

        // Check if doctor is actually a specialist
        $doctor = User::find($request->doctor_id);
        if (!$doctor || $doctor->role !== 'specialist') {
            return redirect()->back()->with([
                'notification' => 'Selected doctor is not a valid specialist.',
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        // Check if reviewer is actually a user (patient)
        $reviewer = User::find($request->reviewer_id);
        if (!$reviewer || $reviewer->role !== 'user') {
            return redirect()->back()->with([
                'notification' => 'Selected reviewer is not a valid patient.',
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        // Check if review already exists for this doctor-reviewer combination (excluding current review)
        $existingReview = DoctorReview::where('doctor_id', $request->doctor_id)
            ->where('reviewer_id', $request->reviewer_id)
            ->where('id', '!=', $review->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with([
                'notification' => 'A review already exists for this doctor by this reviewer.',
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        try {
            $review->update([
                'doctor_id' => $request->doctor_id,
                'reviewer_id' => $request->reviewer_id,
                'comments' => $request->comments,
                'rating' => $request->rating,
                'rating_type' => $request->rating_type,
                'is_approved' => $request->has('is_approved')
            ]);

            return redirect()->route('reviews.index')->with([
                'notification' => 'Review updated successfully.',
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'notification' => 'An error occurred while updating the review: ' . $e->getMessage(),
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }
    }

    /**
     * Toggle approval status of a review
     */
    public function toggleApproval(DoctorReview $review)
    {
        try {
            $review->update([
                'is_approved' => !$review->is_approved
            ]);

            $status = $review->is_approved ? 'approved' : 'unapproved';
            
            return redirect()->back()->with([
                'notification' => "Review {$status} successfully.",
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'notification' => 'An error occurred while updating the review status: ' . $e->getMessage(),
                'status' => NotificationStatus::DANGER->value
            ]);
        }
    }

    /**
     * Get reviews for a specific doctor
     */
    public function doctorReviews($doctorId)
    {
        $doctor = User::where('role', 'specialist')->findOrFail($doctorId);
        $reviews = DoctorReview::with(['reviewer'])
            ->where('doctor_id', $doctorId)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $averageRating = DoctorReview::getAverageRating($doctorId);
        $totalReviews = DoctorReview::getTotalReviews($doctorId);

        return view('admin.reviews.doctor_reviews', compact('doctor', 'reviews', 'averageRating', 'totalReviews'));
    }
} 