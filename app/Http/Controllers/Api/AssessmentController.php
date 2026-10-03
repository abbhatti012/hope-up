<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Option;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    // List questions (optionally filter by assessment_type)
    public function questions(Request $request)
    {
        try {
            $query = Question::with('options')->orderBy('assessment_type')->orderBy('sequence_number');
            if ($request->filled('assessment_type')) {
                $query->where('assessment_type', $request->assessment_type);
            }
            $questions = $query->get();
            return response()->json([
                'success' => true,
                'data' => $questions,
                'message' => 'Questions retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // List options for a question
    public function options($questionId)
    {
        try {
            $question = Question::findOrFail($questionId);
            $options = $question->options()->orderBy('sequence_number')->get();
            return response()->json([
                'success' => true,
                'data' => $options,
                'message' => 'Options retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Submit a user answer
    public function submitAnswer(Request $request)
    {
        try {
            $validated = $request->validate([
                'question_id' => 'required|exists:questions,id',
                'option_id' => 'nullable|exists:options,id',
                'answer_text' => 'nullable|string',
            ]);
            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 401);
            }
            $existing = UserAnswer::where('user_id', $userId)
                ->where('question_id', $validated['question_id'])
                ->first();
            if ($existing) {
                $existing->update([
                    'option_id' => $validated['option_id'] ?? null,
                    'answer_text' => $validated['answer_text'] ?? null,
                ]);
                $answer = $existing->fresh(['question', 'option']);
            } else {
                $answer = UserAnswer::create([
                    'user_id' => $userId,
                    'question_id' => $validated['question_id'],
                    'option_id' => $validated['option_id'] ?? null,
                    'answer_text' => $validated['answer_text'] ?? null,
                ]);
                $answer->load(['question', 'option']);
            }
            return response()->json([
                'success' => true,
                'data' => $answer,
                'message' => 'Answer saved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // List current user's answers
    public function userAnswers()
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 401);
            }
            $answers = UserAnswer::with(['question', 'option'])
                ->where('user_id', $userId)
                ->get();
            return response()->json([
                'success' => true,
                'data' => $answers,
                'message' => 'User answers retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get all answers for a specific user (with user details)
    public function userAnswersForUser($userId)
    {
        try {
            $user = User::with(['doctorDetail', 'patientDetail'])->find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }

            // Prepare user details as in ApiController@get_user
            $userDetails = $user->toArray();
            $userDetails['detail'] = $user->role === 'specialist' ? $user->doctorDetail : $user->patientDetail;
            if ($user->role === 'user' && $user->patientDetail) {
                $userDetails['subscription'] = [
                    'status' => $user->patientDetail->subscription_status,
                    'plan' => $user->patientDetail->subscription_plan,
                    'start_date' => $user->patientDetail->subscription_start_date,
                    'end_date' => $user->patientDetail->subscription_end_date,
                    'trial_ends_at' => $user->patientDetail->trial_ends_at,
                    'is_trial_active' => $user->patientDetail->is_trial_active,
                    'amount' => $user->patientDetail->subscription_amount,
                    'payment_method' => $user->patientDetail->subscription_payment_method,
                ];
            }
            unset($userDetails['doctorDetail'], $userDetails['patientDetail']);

            $answers = UserAnswer::with(['question', 'option'])
                ->where('user_id', $userId)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $userDetails,
                    'answers' => $answers,
                ],
                'message' => 'User answers retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
