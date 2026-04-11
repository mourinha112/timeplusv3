<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->enum('service_mode', ['timeplus', 'particular', 'both'])
                ->default('both')
                ->after('available_time');
        });
    }

    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn('service_mode');
        });
    }
};
