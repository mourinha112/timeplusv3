<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (! Schema::hasColumn('plans', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('duration_days');
            }

            if (! Schema::hasColumn('plans', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'updated_at')) {
                $table->dropColumn('updated_at');
            }

            if (Schema::hasColumn('plans', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });
    }
};
