<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliatesTable extends Migration
{
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index()->unique();
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Product::class, 'product_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 15, 2);

            $table->string('email');
            $table->enum('type', ['percentage', 'value'])->default('percentage');
            $table->string('situation')->default('pending');

            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->name);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
