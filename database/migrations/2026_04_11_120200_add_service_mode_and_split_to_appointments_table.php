<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('service_mode', ['timeplus', 'particular'])
                ->default('particular')
                ->after('total_value');

            $table->decimal('specialist_amount', 10, 2)
                ->default(0)
                ->after('service_mode');

            $table->decimal('platform_amount', 10, 2)
                ->default(0)
                ->after('specialist_amount');

            $table->unsignedSmallInteger('duration_minutes')
                ->default(30)
                ->after('platform_amount');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'service_mode',
                'specialist_amount',
                'platform_amount',
                'duration_minutes',
            ]);
        });
    }
};
