<?php

use App\Enums\PaymentTypeProductEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('paymentType')->default(PaymentTypeProductEnum::UNIQUE->name);
            $table->string('renewsRecurringPayment')->nullable();
            $table->string('guarantee')->nullable()->comment('Garantia em dias');
            $table->string('cyclePayment')->nullable();
            $table->date('endDateRecurringPayment')->nullable();
            $table->integer('numberPaymentsRecurringPayment')->default(12);
            $table->decimal('priceFirstPayment', 15, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'paymentType',
                'guarantee',
                'cyclePayment',
                'endDateRecurringPayment',
                'numberPaymentsRecurringPayment',
                'priceFirstPayment'
            ]);
        });
    }
};
