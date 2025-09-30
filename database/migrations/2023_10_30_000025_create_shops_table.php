<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, 'owner_id')->index()->constrained('users');
            $table->string('name');
            $table->string('username_banking')->unique()->nullable()->comment('Username from suit banking');
            $table->longText('description')->nullable();
            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->name);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
