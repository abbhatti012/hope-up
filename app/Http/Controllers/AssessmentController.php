<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Option;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use App\Enums\NotificationStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;

class AssessmentController extends BaseController
{
    /**
     * Display a listing of questions
     */
    public function index(Request $request)
    {
        try {
            $query = Question::query();
            
            // Apply filters
            if ($request->filled('assessment_type')) {
                $query->where('assessment_type', $request->assessment_type);
            }
            
            if ($request->filled('search')) {
                $query->where('question_text', 'like', '%' . $request->search . '%');
            }
            $perPage = $request->input('per_page', 15);
            $questions = $query->withCount('options')
                              ->orderBy('assessment_type')
                              ->orderBy('sequence_number')
                              ->paginate($perPage);
            
            return view('admin.assessments.listing', compact('questions'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading questions: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new question
     */
    public function create()
    {
        $assessmentTypes = $this->getAssessmentTypes();
        return view('admin.assessments.create', compact('assessmentTypes'));
    }

    /**
     * Store a newly created question
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'question_text' => 'required|string|max:1000',
                'sequence_number' => 'required|integer|min:1',
                'assessment_type' => 'required|in:pre,mid,post',
                'options' => 'required|array|min:2',
                'options.*.option_text' => 'required|string|max:500',
                'options.*.sequence_number' => 'required|integer|min:1',
            ]);

            $existingQuestion = Question::where('assessment_type', $validated['assessment_type'])
                ->where('sequence_number', $validated['sequence_number'])
                ->exists();

            if ($existingQuestion) {
                return redirect()->back()->withInput()->with([
                    'notification' => 'A question with this sequence number already exists for this assessment type.',
                    'status' => NotificationStatus::DANGER->value
                ]);
            }

            DB::transaction(function() use ($validated) {
                $question = Question::create([
                    'question_text' => $validated['question_text'],
                    'sequence_number' => $validated['sequence_number'],
                    'assessment_type' => $validated['assessment_type'],
                ]);

                foreach ($validated['options'] as $optionData) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionData['option_text'],
                        'sequence_number' => $optionData['sequence_number'],
                    ]);
                }
            });

            return redirect()->route('assessments.index')->with([
                'notification' => 'Question created successfully!',
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with([
                'notification' => 'Error creating question: ' . $e->getMessage(),
                'status' => NotificationStatus::DANGER->value
            ]);
        }
    }

    /**
     * Display the specified question
     */
    public function show(Question $question)
    {
        try {
            $question->load('options');
            return view('admin.assessments.show', compact('question'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading question: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified question
     */
    public function edit(Question $question)
    {
        try {
            $question->load('options');
            $assessmentTypes = $this->getAssessmentTypes();
            return view('admin.assessments.edit', compact('question', 'assessmentTypes'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading question: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified question
     */
    public function update(Request $request, Question $question)
    {
        try {
            $validated = $request->validate([
                'question_text' => 'required|string|max:1000',
                'sequence_number' => 'required|integer|min:1',
                'assessment_type' => 'required|in:pre,mid,post',
                'options' => 'required|array|min:2',
                'options.*.option_text' => 'required|string|max:500',
                'options.*.sequence_number' => 'required|integer|min:1',
            ]);

            $existingQuestion = Question::where('assessment_type', $validated['assessment_type'])
                ->where('sequence_number', $validated['sequence_number'])
                ->where('id', '!=', $question->id)
                ->exists();

            if ($existingQuestion) {
                return redirect()->back()->withInput()->with([
                    'notification' => 'A question with this sequence number already exists for this assessment type.',
                    'status' => NotificationStatus::DANGER->value
                ]);
            }

            DB::transaction(function() use ($validated, $question) {
                $question->update([
                    'question_text' => $validated['question_text'],
                    'sequence_number' => $validated['sequence_number'],
                    'assessment_type' => $validated['assessment_type'],
                ]);

                $question->options()->delete();

                foreach ($validated['options'] as $optionData) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionData['option_text'],
                        'sequence_number' => $optionData['sequence_number'],
                    ]);
                }
            });

            return redirect()->route('assessments.index')->with([
                'notification' => 'Question updated successfully!',
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with([
                'notification' => 'Error updating question: ' . $e->getMessage(),
                'status' => NotificationStatus::DANGER->value
            ]);
        }
    }

    /**
     * Remove the specified question
     */
    public function destroy(Question $question)
    {
        try {
            DB::transaction(function() use ($question) {
                $question->options()->delete();
                $question->delete();
            });

            return redirect()->route('assessments.index')->with([
                'notification' => 'Question deleted successfully!',
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'notification' => 'Error deleting question: ' . $e->getMessage(),
                'status' => NotificationStatus::DANGER->value
            ]);
        }
    }

    /**
     * Get assessment types
     */
    private function getAssessmentTypes()
    {
        return [
            'pre' => 'Pre-Assessment',
            'mid' => 'Mid-Assessment', 
            'post' => 'Post-Assessment'
        ];
    }

    /**
     * Store user answer
     */
    public function storeUserAnswer(Request $request)
    {
        try {
            $validated = $request->validate([
                'question_id' => 'required|exists:questions,id',
                'option_id' => 'required|exists:options,id',
                'answer_text' => 'nullable|string',
            ]);

            // Check if user already answered this question
            $existingAnswer = UserAnswer::where('user_id', auth()->id())
                ->where('question_id', $validated['question_id'])
                ->first();

            if ($existingAnswer) {
                // Update existing answer
                $existingAnswer->update([
                    'option_id' => $validated['option_id'],
                    'answer_text' => $validated['answer_text'] ?? null,
                ]);
            } else {
                // Create new answer
                UserAnswer::create([
                    'user_id' => auth()->id(),
                    'question_id' => $validated['question_id'],
                    'option_id' => $validated['option_id'],
                    'answer_text' => $validated['answer_text'] ?? null,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Answer saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving answer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get questions by assessment type
     */
    public function getQuestionsByType($assessmentType)
    {
        try {
            $questions = Question::where('assessment_type', $assessmentType)
                ->with('options')
                ->orderBy('sequence_number')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $questions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading questions: ' . $e->getMessage()
            ], 500);
        }
    }

    public function userAnswers($userId)
    {
        $userAnswers = UserAnswer::with(['question', 'option'])
            ->where('user_id', $userId)
            ->get()
            ->groupBy(fn($a) => $a->question->assessment_type);

        $user = User::findOrFail($userId);

        return view('admin.assessments.user_answers', compact('user', 'userAnswers'));
    }

    public function allUserAnswersList()
    {
        $users = User::where('role', 'user')
            ->whereHas('userAnswers')
            ->get();
        return view('admin.assessments.user_answers_list', compact('users'));
    }
} 