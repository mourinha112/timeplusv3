<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('specialists', function (Blueprint $table) {
            $table->unsignedSmallInteger('session_duration_minutes')
                ->default(30)
                ->after('appointment_value');

            $table->decimal('particular_session_value', 10, 2)
                ->nullable()
                ->after('session_duration_minutes');

            $table->boolean('accepts_timeplus')
                ->default(true)
                ->after('particular_session_value');

            $table->boolean('accepts_particular')
                ->default(true)
                ->after('accepts_timeplus');
        });
    }

    public function down(): void
    {
        Schema::table('specialists', function (Blueprint $table) {
            $table->dropColumn([
                'session_duration_minutes',
                'particular_session_value',
                'accepts_timeplus',
                'accepts_particular',
            ]);
        });
    }
};
