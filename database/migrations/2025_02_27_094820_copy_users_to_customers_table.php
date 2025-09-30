<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        \App\Models\User::select([
            'id',
            'email',
            'name',
            'document_number',
            'phone_number'
        ])->whereNotNull('document_number')->chunk(10, function ($users) {
            foreach ($users as $user) {
                Customer::updateOrCreate([
                    'email' => $user->email,
                    'document_number' => $user->document_number,
                ], [
                    'id'                => $user->id,
                    'name'              => $user->name,
                    'email'             => $user->email,
                    'document_number'   => $user->document_number,
                    'phone_number'      => $user->phone_number
                ]);
            }
        });
    }
};
