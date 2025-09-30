<?php

namespace App\Http\Requests\Member;

use App\Rules\QuizHasOneCorrectOptionRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class CreateQuizRequest extends FormRequest
{
    use MemberRequestHelper;
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:5000', 'nullable'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.Options' => [
                'required',
                'array',
                'min:2',
                new QuizHasOneCorrectOptionRule(),
            ],
            'questions.*.Options.*.text' => ['required', 'string'],
            'questions.*.Options.*.isCorrect' => ['nullable'],
        ];
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'type' => 'quiz',
            'draft' => (bool) ($this->draft === 'true'),
            'moduleId' => $this->moduleId,
            'Quizzes' => $this->treatQuizzes(),
            'Attachments' => $this->getAttachments()
        ];
    }

    private function treatQuizzes(): array
    {
        $quizzes = [];

        foreach ($this->questions as $question) {
            $options = [];

            foreach ($question['Options'] as $option) {
                $options[] = [
                    'text' => $option['text'],
                    'isCorrect' => array_key_exists('isCorrect', $option)
                ];
            }

            $quizzes[] = [
                'question' => $question['question'],
                'Options' => $options
            ];
        }

        return $quizzes;
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
