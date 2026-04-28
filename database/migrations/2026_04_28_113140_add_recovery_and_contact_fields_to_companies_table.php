<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('contact_name')->nullable()->after('email');
            $table->string('contact_role')->nullable()->after('contact_name');
            $table->string('contact_phone', 20)->nullable()->after('contact_role');
            $table->string('recovery_password_token', 64)->nullable()->after('remember_token');
            $table->timestamp('recovery_password_token_expires_at')->nullable()->after('recovery_password_token');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'contact_name',
                'contact_role',
                'contact_phone',
                'recovery_password_token',
                'recovery_password_token_expires_at',
            ]);
        });
    }
};
