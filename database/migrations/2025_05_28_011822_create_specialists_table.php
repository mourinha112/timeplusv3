<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('specialists', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gender_id')->nullable()->constrained('genders');
            $table->foreignId('specialty_id')->nullable()->constrained('specialties');

            $table->string('name');
            $table->string('cpf')->unique();
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('crp')->nullable();
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->year('year_started_acting')->nullable();

            $table->enum('onboarding_step', [
                'personal-details',
                'completed',
            ])->default('personal-details');

            $table->boolean('accepted_terms_contract')->default(true);
            $table->boolean('accepted_privacy_policy')->default(true);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialists');
    }
};
