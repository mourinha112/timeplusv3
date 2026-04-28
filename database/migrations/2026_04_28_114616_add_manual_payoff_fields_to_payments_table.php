<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->timestamp('manual_paid_at')->nullable()->after('paid_at');
            $table->unsignedBigInteger('manual_paid_by_master_id')->nullable()->after('manual_paid_at');
            $table->text('manual_paid_note')->nullable()->after('manual_paid_by_master_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['manual_paid_at', 'manual_paid_by_master_id', 'manual_paid_note']);
        });
    }
};
