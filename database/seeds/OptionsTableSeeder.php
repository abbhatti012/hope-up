<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;
use App\Models\Question;

class OptionsTableSeeder extends Seeder
{
    public function run()
    {
        // Helper: Standard frequency options
        $freqOptions = [
            'Not at all',
            'Several days',
            'More than half the days',
            'Nearly every day',
        ];
        $difficultyOptions = [
            'Not difficult at all',
            'Somewhat difficult',
            'Very difficult',
            'Extremely difficult',
        ];

        // Pre-Assessment
        $preQuestions = Question::where('assessment_type', 'pre')->orderBy('sequence_number')->get();
        foreach ($preQuestions as $q) {
            $opts = ($q->sequence_number == 10) ? $difficultyOptions : $freqOptions;
            foreach ($opts as $i => $text) {
                Option::create([
                    'question_id' => $q->id,
                    'option_text' => $text,
                    'sequence_number' => $i + 1,
                ]);
            }
        }

        // Mid-Assessment
        $midOptions = [
            1 => ['Yes, significant improvement', 'Some improvement', 'No improvement', 'My condition has worsened'],
            2 => ['Daily', 'A few times per week', 'Rarely', 'Never'],
            3 => ['AI chatbot support', 'Virtual therapy sessions', 'Peer support groups', 'Self-help exercises'],
            4 => ['Yes, much more comfortable', 'A little more comfortable', 'No change', 'I feel worse'],
            5 => ['Yes, several', 'A few', 'Not really', 'No, I feel worse'],
            6 => ['Much less often', 'Slightly less often', 'No change', 'More often'],
        ];
        $midQuestions = Question::where('assessment_type', 'mid')->orderBy('sequence_number')->get();
        foreach ($midQuestions as $q) {
            $opts = $midOptions[$q->sequence_number] ?? [];
            foreach ($opts as $i => $text) {
                Option::create([
                    'question_id' => $q->id,
                    'option_text' => $text,
                    'sequence_number' => $i + 1,
                ]);
            }
        }

        // Post-Assessment
        $postOptions = [
            1 => $freqOptions,
            2 => ['Yes, significantly', 'Yes, somewhat', 'No, no noticeable impact', 'No, I feel worse'],
            3 => ['Yes, absolutely', 'Maybe', 'No'],
            4 => ['AI chatbot support', 'Therapy sessions', 'Peer support groups', 'Self-help exercises'],
            5 => ['Yes, much more positive', 'Somewhat positive', 'No change', 'More negative'],
            6 => ['Very satisfied', 'Somewhat satisfied', 'Neutral', 'Dissatisfied'],
            // 7 is open-ended, no options
        ];
        $postQuestions = Question::where('assessment_type', 'post')->orderBy('sequence_number')->get();
        foreach ($postQuestions as $q) {
            $opts = $postOptions[$q->sequence_number] ?? [];
            foreach ($opts as $i => $text) {
                Option::create([
                    'question_id' => $q->id,
                    'option_text' => $text,
                    'sequence_number' => $i + 1,
                ]);
            }
        }
    }
}
