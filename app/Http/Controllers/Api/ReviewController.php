<?php

namespace App\Http\Controllers\Api;

use App\Models\DoctorReview;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * Get all reviews
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'nullable|exists:users,id',
                'reviewer_id' => 'nullable|exists:users,id',
                'search' => 'nullable|string|max:255',
                'rating_type' => 'nullable|in:excellent,very_good,good,fair,poor,bad',
                'is_approved' => 'nullable|boolean',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $reviews = DoctorReview::with([
                    'doctor', 
                    'doctor.doctorDetail',
                    'reviewer',
                    'reviewer.patientDetail'
                ])
                ->when($request->doctor_id, function($query, $doctorId) {
                    return $query->where('doctor_id', $doctorId);
                })
                ->when($request->reviewer_id, function($query, $reviewerId) {
                    return $query->where('reviewer_id', $reviewerId);
                })
                ->when($request->search, function($query) use ($request) {
                    return $query->where(function($q) use ($request) {
                        $q->where('comments', 'like', '%' . $request->search . '%')
                          ->orWhere('rating_type', 'like', '%' . $request->search . '%');
                    });
                })
                ->when($request->has('rating_type'), function($query) use ($request) {
                    return $query->where('rating_type', $request->rating_type);
                })
                ->when($request->is_approved !== null, function($query) use ($request) {
                    return $query->where('is_approved', $request->is_approved === '1' || $request->is_approved === 1);
                })
                ->orderBy('created_at', 'desc')
                ->paginate($request->per_page ?? 15);

            // Transform the reviews to include detailed user information
            $transformedReviews = $reviews->getCollection()->map(function($review) {
                return $this->formatReviewWithUserDetails($review);
            });

            $reviews->setCollection($transformedReviews);

            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching reviews: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reviews',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get a specific review
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid review ID'
                ], 400);
            }

            $review = DoctorReview::with([
                    'doctor', 
                    'doctor.doctorDetail',
                    'reviewer',
                    'reviewer.patientDetail'
                ])->find($id);
            
            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatReviewWithUserDetails($review)
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching review #' . $id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch review',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a new review
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|exists:users,id',
                'reviewer_id' => 'required|exists:users,id',
                'comments' => 'nullable|string|max:1000',
                'rating' => 'required|integer|between:1,5',
                'rating_type' => 'required|in:excellent,very_good,good,fair,poor,bad',
                'is_approved' => 'sometimes|boolean',
            ], [
                'doctor_id.required' => 'Doctor ID is required',
                'doctor_id.exists' => 'The selected doctor does not exist',
                'reviewer_id.required' => 'Reviewer ID is required',
                'reviewer_id.exists' => 'The selected reviewer does not exist',
                'rating.required' => 'Rating is required',
                'rating.between' => 'Rating must be between 1 and 5',
                'rating_type.required' => 'Rating type is required',
                'rating_type.in' => 'Invalid rating type',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if doctor is actually a specialist
            $doctor = User::find($request->doctor_id);
            if (!$doctor || $doctor->role !== 'specialist') {
                return response()->json([
                    'success' => false,
                    'message' => 'The provided doctor is not a valid specialist.'
                ], 400);
            }

            // Check if reviewer is not the same as doctor
            if ($request->doctor_id == $request->reviewer_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Doctor cannot review themselves.'
                ], 400);
            }

            // Check if reviewer has already reviewed this doctor
            $existingReview = DoctorReview::where('doctor_id', $request->doctor_id)
                ->where('reviewer_id', $request->reviewer_id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this doctor.'
                ], 409);
            }

            DB::beginTransaction();

            try {
                $review = DoctorReview::create([
                    'doctor_id' => $request->doctor_id,
                    'reviewer_id' => $request->reviewer_id,
                    'comments' => $request->comments,
                    'rating' => $request->rating,
                    'rating_type' => $request->rating_type,
                    'is_approved' => $request->boolean('is_approved', false)
                ]);

                $review->load([
                    'doctor', 
                    'doctor.doctorDetail',
                    'reviewer',
                    'reviewer.patientDetail'
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Review created successfully.',
                    'data' => $this->formatReviewWithUserDetails($review)
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error creating review: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create review',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update a review
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid review ID'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'doctor_id' => 'sometimes|exists:users,id',
                'reviewer_id' => 'sometimes|exists:users,id',
                'comments' => 'nullable|string|max:1000',
                'rating' => 'sometimes|integer|between:1,5',
                'rating_type' => 'sometimes|in:excellent,very_good,good,fair,poor,bad',
                'is_approved' => 'sometimes|boolean',
            ], [
                'doctor_id.exists' => 'The selected doctor does not exist',
                'reviewer_id.exists' => 'The selected reviewer does not exist',
                'rating.integer' => 'Rating must be an integer',
                'rating.between' => 'Rating must be between 1 and 5',
                'rating_type.in' => 'Invalid rating type',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $review = DoctorReview::find($id);
            
            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found.'
                ], 404);
            }

            // If doctor_id is being updated, verify it's a specialist
            if ($request->has('doctor_id')) {
                $doctor = User::find($request->doctor_id);
                if (!$doctor || $doctor->role !== 'specialist') {
                    return response()->json([
                        'success' => false,
                        'message' => 'The provided doctor is not a valid specialist.'
                    ], 400);
                }
            }

            // If reviewer_id is being updated
            if ($request->has('reviewer_id')) {
                // Check if reviewer is not the same as doctor
                $doctorId = $request->has('doctor_id') ? $request->doctor_id : $review->doctor_id;
                if ($doctorId == $request->reviewer_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Doctor cannot review themselves.'
                    ], 400);
                }

                // Check if the new reviewer has already reviewed this doctor
                $existingReview = DoctorReview::where('doctor_id', $doctorId)
                    ->where('reviewer_id', $request->reviewer_id)
                    ->where('id', '!=', $id)
                    ->exists();

                if ($existingReview) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This reviewer has already reviewed the specified doctor.'
                    ], 409);
                }
            }

            DB::beginTransaction();

            try {
                $updateData = array_filter($request->only([
                    'doctor_id',
                    'reviewer_id',
                    'comments',
                    'rating',
                    'rating_type',
                    'is_approved'
                ]), function($value) {
                    return $value !== null;
                });

                $review->update($updateData);
                $review->load([
                    'doctor', 
                    'doctor.doctorDetail',
                    'reviewer',
                    'reviewer.patientDetail'
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Review updated successfully.',
                    'data' => $this->formatReviewWithUserDetails($review)
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error updating review #' . $id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Delete a review
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid review ID'
                ], 400);
            }

            $review = DoctorReview::find($id);
            
            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found.'
                ], 404);
            }

            DB::beginTransaction();

            try {
                $review->delete();
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Review deleted successfully.'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error deleting review #' . $id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get reviews for a specific doctor
     * 
     * @param int $doctorId
     * @return \Illuminate\Http\JsonResponse
     */
    public function doctorReviews($doctorId)
    {
        try {
            if (!is_numeric($doctorId) || $doctorId <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid doctor ID'
                ], 400);
            }

            $doctor = User::with('doctorDetail')->find($doctorId);
            
            if (!$doctor || $doctor->role !== 'specialist') {
                return response()->json([
                    'success' => false,
                    'message' => 'The provided ID does not belong to a valid doctor.'
                ], 404);
            }

            $reviews = DoctorReview::with(['reviewer', 'reviewer.patientDetail'])
                ->where('doctor_id', $doctorId)
                ->where('is_approved', true)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($review) {
                    return $this->formatReviewWithUserDetails($review);
                });

            // Format doctor details
            $formattedDoctor = [
                'id' => $doctor->id,
                'first_name' => $doctor->first_name,
                'last_name' => $doctor->last_name,
                'email' => $doctor->email,
                'profile_photo' => $doctor->profile_photo,
                'doctor_detail' => $doctor->doctorDetail ? [
                    'speciality' => $doctor->doctorDetail->speciality,
                    'experience' => $doctor->doctorDetail->experience,
                    'about' => $doctor->doctorDetail->about,
                ] : null
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'doctor' => $formattedDoctor,
                    'reviews' => $reviews,
                    'average_rating' => $reviews->avg('rating'),
                    'total_reviews' => $reviews->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching doctor reviews for doctor #' . $doctorId . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch doctor reviews',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Toggle approval status of a review
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleApproval($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid review ID'
                ], 400);
            }

            $review = DoctorReview::find($id);
            
            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found.'
                ], 404);
            }

            DB::beginTransaction();

            try {
                $review->is_approved = !$review->is_approved;
                $review->save();
                
                $review->load([
                    'doctor', 
                    'doctor.doctorDetail',
                    'reviewer',
                    'reviewer.patientDetail'
                ]);

                $status = $review->is_approved ? 'approved' : 'unapproved';
                
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => "Review {$status} successfully.",
                    'data' => $this->formatReviewWithUserDetails($review)
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error toggling approval for review #' . $id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle review approval',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Format review with detailed user information
     * 
     * @param DoctorReview $review
     * @return array
     */
    protected function formatReviewWithUserDetails(DoctorReview $review)
    {
        $formattedReview = $review->toArray();
        
        // Format doctor details
        if ($review->relationLoaded('doctor')) {
            $formattedReview['doctor_details'] = [
                'id' => $review->doctor->id,
                'first_name' => $review->doctor->first_name,
                'last_name' => $review->doctor->last_name,
                'email' => $review->doctor->email,
                'profile_photo' => $review->doctor->profile_photo,
                'role' => $review->doctor->role,
            ];

            if ($review->doctor->relationLoaded('doctorDetail') && $review->doctor->doctorDetail) {
                $formattedReview['doctor_details']['speciality'] = $review->doctor->doctorDetail->speciality;
                $formattedReview['doctor_details']['experience'] = $review->doctor->doctorDetail->experience;
                $formattedReview['doctor_details']['about'] = $review->doctor->doctorDetail->about;
            }
        }

        // Format reviewer (patient) details
        if ($review->relationLoaded('reviewer')) {
            $formattedReview['reviewer_details'] = [
                'id' => $review->reviewer->id,
                'first_name' => $review->reviewer->first_name,
                'last_name' => $review->reviewer->last_name,
                'email' => $review->reviewer->email,
                'profile_photo' => $review->reviewer->profile_photo,
                'role' => $review->reviewer->role,
            ];

            if ($review->reviewer->relationLoaded('patientDetail') && $review->reviewer->patientDetail) {
                $formattedReview['reviewer_details']['phone_number'] = $review->reviewer->patientDetail->phone_number;
                $formattedReview['reviewer_details']['gender'] = $review->reviewer->patientDetail->gender;
                $formattedReview['reviewer_details']['age'] = $review->reviewer->patientDetail->calculated_age;
            }
        }

        return $formattedReview;
    }
}
