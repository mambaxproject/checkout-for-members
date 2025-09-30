<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('abandoned_carts', function (Blueprint $table) {
            $table->boolean('email_notification_sent')->after('infosProduct')->default(0);
            $table->boolean('whatsapp_notification_sent')->after('infosProduct')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('abandoned_carts', function (Blueprint $table) {
            $table->dropColumn(['email_notification_sent', 'whatsapp_notification_sent']);
        });
    }
};
