<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionsTableSeeder extends Seeder
{
    public function run()
    {
        // Pre-Assessment Questions
        $preQuestions = [
            'Over the last two weeks, how often have you experienced little interest or pleasure in doing things?',
            'How often have you felt down, depressed, or hopeless?',
            'Have you had trouble falling or staying asleep, or sleeping too much?',
            'Have you felt tired or had little energy?',
            'How often have you had poor appetite or been overeating?',
            'Have you felt bad about yourself, or that you are a failure or have let yourself or your family down?',
            'Have you had trouble concentrating on things, such as reading or watching TV?',
            'Have you been moving or speaking so slowly that other people noticed, or the opposite—being so restless that you move a lot more than usual?',
            'Have you had thoughts that you would be better off dead or thoughts of hurting yourself in some way?',
            'If you checked any problems above, how difficult have these problems made it for you to do your work, take care of things at home, or get along with other people?',
        ];
        foreach ($preQuestions as $i => $text) {
            Question::create([
                'question_text' => $text,
                'sequence_number' => $i + 1,
                'assessment_type' => 'pre',
            ]);
        }

        // Mid-Assessment Questions
        $midQuestions = [
            'Have you noticed any improvement in your mood and ability to enjoy activities?',
            'How often do you use the mental health resources or interact with a therapist on the app?',
            'Which feature of the app has been the most helpful for your mental health?',
            'Do you feel more comfortable discussing your mental health concerns after using the app?',
            'Have you developed any new coping strategies since using the app?',
            'How often have you experienced thoughts of self-harm or suicidal ideation compared to before using the app?',
        ];
        foreach ($midQuestions as $i => $text) {
            Question::create([
                'question_text' => $text,
                'sequence_number' => $i + 1,
                'assessment_type' => 'mid',
            ]);
        }

        // Post-Assessment Questions
        $postQuestions = [
            'Over the last two weeks, how often have you felt little interest or pleasure in doing things?',
            'Do you feel that the app has contributed positively to your mental health?',
            'Would you recommend this app to others facing mental health challenges?',
            'Which feature was the most beneficial in managing your mental health?',
            'Have you developed a more positive outlook on your mental health journey?',
            'How satisfied are you with the professional support provided through the app?',
            'What improvements would you suggest for the app?',
        ];
        foreach ($postQuestions as $i => $text) {
            Question::create([
                'question_text' => $text,
                'sequence_number' => $i + 1,
                'assessment_type' => 'post',
            ]);
        }
    }
}
