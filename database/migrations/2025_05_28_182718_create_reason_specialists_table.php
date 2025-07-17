<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('reason_specialists', function (Blueprint $table) {
            $table->foreignId('reason_id')->constrained()->onDelete('cascade');
            $table->foreignId('specialist_id')->constrained()->onDelete('cascade');

            $table->unique(['reason_id', 'specialist_id'], 'unique_reason_specialist');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reason_specialists');
    }
};
