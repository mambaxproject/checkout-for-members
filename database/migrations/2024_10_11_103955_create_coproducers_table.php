<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('coproducers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->index()->constrained('users');
            $table->string('name');
            $table->string('email');
            $table->boolean('allow_affiliate_sales')->default(0);
            $table->boolean('allow_producer_sales')->default(0);
            $table->decimal('percentage_commission_producer_sales', 5, 2)->nullable();
            $table->decimal('percentage_commission_affiliates_sales', 5, 2)->nullable();
            $table->dateTime('valid_until_at_producer_sales')->nullable();
            $table->dateTime('valid_until_at_affiliates_sales')->nullable();
            $table->string('situation')->default(\App\Enums\SituationAffiliateEnum::PENDING->name);
            $table->schemalessAttributes('attributes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coproducers');
    }

};
