<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AddManyStudentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'archive' => ['required', 'file', 'max:2048']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->hasFile('archive')) {
                return;
            }

            $file = $this->file('archive');
            $tempPath = storage_path('app/tmp/' . $file->getClientOriginalName());
            copy($file->getRealPath(), $tempPath);
            $rows = (new FastExcel())->import($tempPath);
            unlink($tempPath);

            if ($rows->isEmpty()) {
                $validator->errors()->add('archive', 'O arquivo está vazio.');
                return;
            }

            $expectedHeaders = ['nome', 'email', 'documento'];
            $actualHeaders = array_map(fn($h) => Str::slug(trim($h), '_'), array_keys($rows->first()));

            if ($expectedHeaders !== $actualHeaders) {
                $validator->errors()->add('archive', 'Cabeçalho inválido. Esperado: nome, email, documento (nesta ordem).');
                return;
            }

            $errors = [];
            $limit = 2000;

            if ($rows->count() > $limit) {
                $validator->errors()->add('archive', "O arquivo excede o limite máximo de {$limit} linhas.");
                return;
            }

            foreach ($rows as $i => $row) {
                $rowNumber = $i + 2;

                $data = [
                    'name' => $row['nome'] ?? null,
                    'email' => $row['email'] ?? null,
                    'document' => $row['documento'] ?? null,
                ];

                $rowValidator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'document' => 'required|string|max:20',
                ], [
                    'required' => "O campo :attribute é obrigatório na linha {$rowNumber}.",
                    'string' => "O campo :attribute deve ser um texto na linha {$rowNumber}.",
                    'max' => "O campo :attribute excede o tamanho máximo permitido na linha {$rowNumber}.",
                    'email' => "O campo email deve ser um endereço de e-mail válido na linha {$rowNumber}.",
                ], [
                    'name' => 'nome',
                    'email' => 'email',
                    'document' => 'documento',
                ]);

                if ($rowValidator->fails()) {
                    foreach ($rowValidator->errors()->all() as $message) {
                        $errors[] = $message;
                    }
                }
            }

            if (!empty($errors)) {
                throw ValidationException::withMessages(['archive' => $errors]);
            }
        });
    }

    public function messages()
    {
        return [
            'archive.required' => 'O arquivo é obrigatório.',
            'archive.file' => 'O arquivo deve ser um arquivo válido.',
            'archive.mimes' => 'O arquivo deve ser do tipo: xlsx ou csv.',
            'archive.max' => 'O arquivo não pode ser maior que 2 MB.',
        ];
    }

    public function toArray(): array
    {
        $file = $this->file('archive');
        $tempPath = storage_path('app/tmp/' . $file->getClientOriginalName());
        copy($file->getRealPath(), $tempPath);

        if (!$this->file('archive')) {
            throw new \Exception('Arquivo não foi armazenado para processamento.');
        }
     
        $rows = (new FastExcel())->import($tempPath);

        $members = $rows->map(function ($row) {
            return [
                'name' => $row['nome'],
                'email' => $row['email'],
                'document' => preg_replace('/\D/', '', $row['documento']),
            ];
        })->toArray();

        return ['members' => $members, 'offer' => $this->offer];
    }
}
