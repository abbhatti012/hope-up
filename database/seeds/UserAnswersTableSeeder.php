<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAnswer;
use App\Models\Question;
use App\Models\Option;
use App\Models\User;

class UserAnswersTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('role', 'user')->first();
        if (!$user) return;
        $userId = $user->id;
        // Pre-Assessment: answer first 3 questions
        $preQuestions = Question::where('assessment_type', 'pre')->orderBy('sequence_number')->take(3)->get();
        foreach ($preQuestions as $i => $q) {
            $option = Option::where('question_id', $q->id)->orderBy('sequence_number')->skip($i)->first();
            UserAnswer::create([
                'user_id' => $userId,
                'question_id' => $q->id,
                'option_id' => $option ? $option->id : null,
                'answer_text' => null,
            ]);
        }
        // Mid-Assessment: answer first 2 questions
        $midQuestions = Question::where('assessment_type', 'mid')->orderBy('sequence_number')->take(2)->get();
        foreach ($midQuestions as $i => $q) {
            $option = Option::where('question_id', $q->id)->orderBy('sequence_number')->skip($i)->first();
            UserAnswer::create([
                'user_id' => $userId,
                'question_id' => $q->id,
                'option_id' => $option ? $option->id : null,
                'answer_text' => null,
            ]);
        }
        // Post-Assessment: answer first 2 questions, and open-ended for last
        $postQuestions = Question::where('assessment_type', 'post')->orderBy('sequence_number')->get();
        foreach ($postQuestions as $i => $q) {
            if ($q->sequence_number == 7) {
                UserAnswer::create([
                    'user_id' => $userId,
                    'question_id' => $q->id,
                    'option_id' => null,
                    'answer_text' => 'I would like to see more meditation exercises.',
                ]);
            } elseif ($i < 2) {
                $option = Option::where('question_id', $q->id)->orderBy('sequence_number')->skip($i)->first();
                UserAnswer::create([
                    'user_id' => $userId,
                    'question_id' => $q->id,
                    'option_id' => $option ? $option->id : null,
                    'answer_text' => null,
                ]);
            }
        }
    }
}
