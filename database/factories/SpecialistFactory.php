<?php

namespace Database\Factories;

use App\Models\{Gender, Specialty};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SpecialistFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'                => fake()->name(),
            'email'               => fake()->unique()->safeEmail(),
            'cpf'                 => preg_replace('/[^0-9]/', '', fake()->numerify('###.###.###-##')),
            'phone_number'        => preg_replace('/[^0-9]/', '', fake()->numerify('(##) #####-####')),
            'email_verified_at'   => now(),
            'password'            => static::$password ??= Hash::make('password'),
            'year_started_acting' => fake()->year(),
            'crp'                 => fake()->numerify('######'),
            'gender_id'           => Gender::inRandomOrder()->first()->id,
            'specialty_id'        => Specialty::inRandomOrder()->first()->id,
            'remember_token'      => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
