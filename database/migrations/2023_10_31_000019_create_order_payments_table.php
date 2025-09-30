<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_method');
            $table->decimal('amount', 15, 2);
            $table->string('payment_status');

            $table->longText('payment_gateway_response')->nullable();
            $table->string('external_identification')->nullable();
            $table->text('external_url')->nullable();
            $table->text('external_content')->nullable();
            $table->integer('installments')->nullable();
            $table->integer('installment_amount')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('due_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
