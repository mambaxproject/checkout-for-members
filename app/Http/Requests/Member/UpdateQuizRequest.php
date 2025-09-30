<?php

namespace App\Http\Requests\Member;

use App\Rules\QuizHasOneCorrectOptionRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class UpdateQuizRequest extends FormRequest
{

    use MemberRequestHelper;

    public function rules()
    {
        return [
            'name' => ['string', 'max:255'],
            'description' => ['string', 'max:5000', 'nullable'],
            'questions' => ['array', 'min:1'],
            'questions.*.question' => ['string'],
            'questions.*.Options' => [
                'array',
                'min:2',
                new QuizHasOneCorrectOptionRule(),
            ],
            'questions.*.Options.*.text' => ['string'],
            'questions.*.Options.*.isCorrect' => ['nullable'],
        ];
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'draft' => (bool) ($this->draft === 'true'),
            'Quizzes' => $this->treatQuizzes(),
            'Attachments' => $this->getAttachments()
        ];

        return $this->removeUnchangedFields($data);
    }

    private function treatQuizzes(): array
    {
        $result = [
            'update' => [],
            'add' => [],
        ];

        foreach ($this->questions as $question) {
            $hasQuizId = isset($question['quizId']);
            $options = [];

            foreach ($question['Options'] as $option) {
                $optionData = [
                    'id' => $option['id'] ?? null,
                    'text' => $option['text'],
                    'isCorrect' => array_key_exists('isCorrect', $option),
                ];

                if (isset($option['id'])) {
                    $optionData['id'] = $option['id'];
                }

                $options[] = $optionData;
            }

            $questionData = [
                'question' => $question['question'],
                'lessonId' => $this->lessonId,
                'Options' => $options,
            ];

            if ($hasQuizId) {
                $questionData['quizId'] = $question['quizId'];
                $result['update'][] = $questionData;
            } else {
                $result['add'][] = $questionData;
            }
        }

        return $result;
    }

    private function getAttachments(): array
    {
        if (empty($this->attachments)) {
            return [];
        }

        $attachments = [];

        foreach ($this->attachments as $attachment) {
            $attachments[] = [
                'url' => $this->saveS3($attachment),
                'type' => $attachment->getClientOriginalExtension()
            ];
        }

        return $attachments;
    }
}
