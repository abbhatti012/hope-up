<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;

class QuizController extends BaseController
{



    // Base method for creating resources
    protected function createResource($model, $data)
    {
        return $model::create($data);
    }

    // Base method for updating resources
    protected function updateResource($model, $id, $data)
    {
        $resource = $model::findOrFail($id);
        $resource->update($data);
        return $resource;
    }

    // Base method for deleting resources
    protected function deleteResource($model, $id)
    {
        $resource = $model::findOrFail($id);
        $resource->delete();
        return response()->json([
            'message' => 'Resource deleted successfully'
        ]);
    }

    // Base method for getting a single resource
    protected function getResource($model, $id)
    {
        return $model::findOrFail($id);
    }

    // Base method for listing resources
    protected function listResources($query, $paginate = true, $perPage = 15)
    {
        return $paginate ? $query->paginate($perPage) : $query->get();
    }
    // Get all quizzes
    public function index(Request $request)
    {
        return $this->handleErrors(function() use ($request) {
            $validated = $this->validateRequest($request, [
                'category' => 'nullable|string',
                'is_active' => 'nullable|boolean',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);

            $query = Quiz::query();
            
            if (isset($validated['category'])) {
                $query->where('category', $validated['category']);
            }
            
            if (isset($validated['is_active'])) {
                $query->where('is_active', $validated['is_active']);
            }
            
            $query->orderBy('created_at', 'desc');

            $quizzes = $this->listResources($query, true, $validated['limit'] ?? 15);

            return response()->json(['data' => $quizzes]);
        });
    }

    // Get a specific quiz with questions and options
    public function show($quizId)
    {
        return $this->handleErrors(function() use ($quizId) {
            $quiz = $this->getResource(Quiz::class, $quizId)->load([
                'questions' => function ($query) {
                    $query->orderBy('sequence_number');
                },
                'questions.options' => function ($query) {
                    $query->orderBy('sequence_number');
                }
            ]);

            return response()->json(['data' => $quiz]);
        });
    }

    // Create a new quiz
    public function store(Request $request)
    {
        return $this->handleErrors(function() use ($request) {
            $validated = $this->validateRequest($request, [
                'category' => 'required|string',
                'title' => 'required|string',
                'description' => 'nullable|string',
                'total_questions' => 'required|integer',
                'time_limit_minutes' => 'required|integer',
                'is_active' => 'required|boolean',
            ]);

            $quiz = $this->createResource(Quiz::class, $validated);

            return response()->json([
                'message' => 'Quiz created successfully',
                'data' => $quiz
            ], 201);
        });
    }

    // Start a quiz attempt
    public function startQuiz($quizId)
    {
        return $this->handleErrors(function() use ($quizId) {
            $quiz = $this->getResource(Quiz::class, $quizId);
            
            // Check if a quiz attempt already exists for this user and quiz
            $existingAttempt = QuizAttempt::where('user_id', Auth::id())
                ->where('quiz_id', $quizId)
                ->exists();

            if ($existingAttempt) {
                throw new \Exception('You already have an attempt for this quiz');
            }

            $attempt = $this->createResource(QuizAttempt::class, [
                'user_id' => Auth::id(),
                'quiz_id' => $quizId,
                'total_questions' => $quiz->total_questions,
                'correct_answers' => 0,
                'time_taken_seconds' => 0,
                'is_completed' => false,
            ]);

            return response()->json([
                'message' => 'Quiz started successfully',
                'data' => $attempt
            ]);
        });
    }

    // Submit an answer for a question
    public function submitAnswer(Request $request, $quizAttemptId, $questionId)
    {
        return $this->handleErrors(function() use ($request, $quizAttemptId, $questionId) {
            $validated = $this->validateRequest($request, [
                'option_id' => 'required|exists:options,id',
            ]);

            $attempt = $this->getResource(QuizAttempt::class, $quizAttemptId);
            if ($attempt->user_id !== Auth::id()) {
                throw new \Exception('Unauthorized attempt access');
            }

            $question = $this->getResource(Question::class, $questionId);

            $isCorrect = Option::where('id', $validated['option_id'])
                ->where('is_correct', true)
                ->exists();

            $answer = $this->createResource(QuizAttemptAnswer::class, [
                'quiz_attempt_id' => $quizAttemptId,
                'question_id' => $questionId,
                'option_id' => $validated['option_id'],
                'is_correct' => $isCorrect,
            ]);

            return response()->json([
                'message' => 'Answer submitted successfully',
                'data' => [
                    'is_correct' => $isCorrect
                ]
            ]);
        });
    }

    // Complete a quiz
    public function completeQuiz($quizAttemptId)
    {
        return $this->handleErrors(function() use ($quizAttemptId) {
            $attempt = $this->getResource(QuizAttempt::class, $quizAttemptId);
            if ($attempt->user_id !== Auth::id()) {
                throw new \Exception('Unauthorized attempt access');
            }

            $answers = QuizAttemptAnswer::where('quiz_attempt_id', $quizAttemptId)
                ->get();

            $score = $answers->where('is_correct', true)->count();
            $correctAnswers = $score;
            $totalQuestions = $attempt->total_questions;
            $timeTaken = now()->diffInSeconds($attempt->created_at);

            $attempt->update([
                'score' => $score,
                'correct_answers' => $correctAnswers,
                'time_taken_seconds' => $timeTaken,
                'is_completed' => true,
            ]);

            return response()->json([
                'message' => 'Quiz completed successfully',
                'data' => $attempt
            ]);
        });
    }

    // Get user's quiz attempts
    public function getAttempts(Request $request)
    {
        return $this->handleErrors(function() use ($request) {
            $validated = $this->validateRequest($request, [
                'quiz_id' => 'nullable|integer|exists:quizzes,id',
                'is_completed' => 'nullable|boolean',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);

            $query = QuizAttempt::where('user_id', Auth::id());

            if (isset($validated['quiz_id'])) {
                $query->where('quiz_id', $validated['quiz_id']);
            }

            if (isset($validated['is_completed'])) {
                $query->where('is_completed', $validated['is_completed']);
            }

            $query->with('quiz')
                ->orderBy('created_at', 'desc');

            $attempts = $this->listResources($query, true, $validated['limit'] ?? 15);

            return response()->json(['data' => $attempts]);
        });
    }

    // Get quiz statistics
    public function getQuizStats($quizId)
    {
        return $this->handleErrors(function() use ($quizId) {
            $quiz = $this->getResource(Quiz::class, $quizId);

            $stats = [
                'quiz_id' => $quizId,
                'total_attempts' => QuizAttempt::where('quiz_id', $quizId)->count(),
                'average_score' => QuizAttempt::where('quiz_id', $quizId)
                    ->where('is_completed', true)
                    ->avg('score'),
                'highest_score' => QuizAttempt::where('quiz_id', $quizId)
                    ->where('is_completed', true)
                    ->max('score'),
                'lowest_score' => QuizAttempt::where('quiz_id', $quizId)
                    ->where('is_completed', true)
                    ->min('score'),
            ];

            return response()->json(['data' => $stats]);
        });
    }

    // Question Management
    public function createQuestion(Request $request)
    {
        return $this->handleErrors(function() use ($request) {
            // Validate JSON data
            $validated = $this->validateRequest($request, [
                'quiz_id' => 'required|integer|exists:quizzes,id',
                'question_text' => 'required|string',
                'sequence_number' => 'required|integer',
                'type' => 'required|in:multiple_choice,true_false',
                'points' => 'required|integer',
                'assessment_type' => 'required|in:pre,mid,post',
                'options' => 'required|array',
                'options.*.option_text' => 'required|string',
                'options.*.is_correct' => 'required|boolean',
                'options.*.sequence_number' => 'required|integer',
            ]);

            // Check if sequence number already exists for this quiz
            $existingQuestion = Question::where('quiz_id', $validated['quiz_id'])
                ->where('sequence_number', $validated['sequence_number'])
                ->exists();

            if ($existingQuestion) {
                throw new \Exception('A question with this sequence number already exists for this quiz');
            }

            // Ensure sequence numbers are unique within this question
            $optionSequenceNumbers = array_column($validated['options'], 'sequence_number');
            if (count($optionSequenceNumbers) !== count(array_unique($optionSequenceNumbers))) {
                throw new \Exception('Duplicate sequence numbers found in options');
            }

            // Create question
            $question = $this->createResource(Question::class, [
                'quiz_id' => $validated['quiz_id'],
                'question_text' => $validated['question_text'],
                'sequence_number' => $validated['sequence_number'],
                'type' => $validated['type'],
                'points' => $validated['points'],
            ]);

            // Create options
            foreach ($validated['options'] as $option) {
                $this->createResource(Option::class, [
                    'question_id' => $question->id,
                    'option_text' => $option['option_text'],
                    'is_correct' => $option['is_correct'],
                    'sequence_number' => $option['sequence_number'],
                ]);
            }

            return response()->json([
                'message' => 'Question created successfully',
                'data' => $question->load('options')
            ], 201);
        });
    }

    public function getQuestions(Request $request)
    {
        return $this->handleErrors(function() use ($request) {
            $validated = $this->validateRequest($request, [
                'quiz_id' => 'nullable|integer|exists:quizzes,id',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);

            $query = Question::query()
                ->when(isset($validated['quiz_id']), function ($query) use ($validated) {
                    $query->where('quiz_id', $validated['quiz_id']);
                })
                ->with('options')
                ->orderBy('sequence_number');

            $questions = $this->listResources($query, true, $validated['limit'] ?? 15);

            return response()->json(['data' => $questions]);
        });
    }

    public function getQuestion($questionId)
    {
        return $this->handleErrors(function() use ($questionId) {
            $question = $this->getResource(Question::class, $questionId)->load('options');
            return response()->json(['data' => $question]);
        });
    }

    public function updateQuestion(Request $request, $questionId)
    {
        return $this->handleErrors(function() use ($request, $questionId) {
            $validated = $this->validateRequest($request, [
                'question_text' => 'string',
                'sequence_number' => 'integer',
                'type' => 'in:multiple_choice,true_false',
                'points' => 'integer',
                'assessment_type' => 'in:pre,mid,post',
                'options' => 'array',
                'options.*.option_text' => 'string',
                'options.*.is_correct' => 'boolean',
                'options.*.sequence_number' => 'integer',
            ]);

            $question = $this->getResource(Question::class, $questionId);

            // Update question
            $question = $this->updateResource(Question::class, $questionId, $validated);

            // Update options if provided
            if (isset($validated['options'])) {
                Option::where('question_id', $questionId)->delete();
                foreach ($validated['options'] as $option) {
                    $this->createResource(Option::class, [
                        'question_id' => $questionId,
                        'option_text' => $option['option_text'],
                        'is_correct' => $option['is_correct'],
                        'sequence_number' => $option['sequence_number'],
                    ]);
                }
            }

            return response()->json([
                'message' => 'Question updated successfully',
                'data' => $question->load('options')
            ]);
        });
    }

    public function deleteQuestion($questionId)
    {
        return $this->handleErrors(function() use ($questionId) {
            return $this->deleteResource(Question::class, $questionId);
        });
    }

    // Option Management
    public function createOption(Request $request)
    {
        return $this->handleErrors(function() use ($request) {
            $validated = $this->validateRequest($request, [
                'question_id' => 'required|integer|exists:questions,id',
                'option_text' => 'required|string',
                'is_correct' => 'required|boolean',
                'sequence_number' => 'required|integer',
            ]);

            // Check if sequence number already exists for this question
            $existingOption = Option::where('question_id', $validated['question_id'])
                ->where('sequence_number', $validated['sequence_number'])
                ->exists();

            if ($existingOption) {
                throw new \Exception('Sequence number already exists for this question');
            }

            $option = $this->createResource(Option::class, $validated);

            return response()->json([
                'message' => 'Option created successfully',
                'data' => $option
            ], 201);
        });
    }

    public function getOptions(Request $request)
    {
        return $this->handleErrors(function() use ($request) {
            $validated = $this->validateRequest($request, [
                'question_id' => 'required|integer|exists:questions,id',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);

            $query = Option::where('question_id', $validated['question_id'])
                ->orderBy('sequence_number');

            $options = $this->listResources($query, true, $validated['limit'] ?? 15);

            return response()->json(['data' => $options]);
        });
    }

    public function getOption($optionId)
    {
        return $this->handleErrors(function() use ($optionId) {
            $option = $this->getResource(Option::class, $optionId);
            return response()->json(['data' => $option]);
        });
    }

    public function updateOption(Request $request, $optionId)
    {
        return $this->handleErrors(function() use ($request, $optionId) {
            $validated = $this->validateRequest($request, [
                'option_text' => 'string',
                'is_correct' => 'boolean',
                'sequence_number' => 'integer',
            ]);

            $option = $this->updateResource(Option::class, $optionId, $validated);

            return response()->json([
                'message' => 'Option updated successfully',
                'data' => $option
            ]);
        });
    }

    public function deleteOption($optionId)
    {
        return $this->handleErrors(function() use ($optionId) {
            return $this->deleteResource(Option::class, $optionId);
        });
    }
}
