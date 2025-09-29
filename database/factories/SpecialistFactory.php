<?php

namespace Database\Factories;

use App\Models\{Gender, Reason, Specialist, Specialty};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class SpecialistFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'                => fake()->name(),
            'email'               => fake()->unique()->safeEmail(),
            'birth_date'          => fake()->date('d/m/Y', '-25 years'),
            'cpf'                 => preg_replace('/[^0-9]/', '', fake()->numerify('###.###.###-##')),
            'phone_number'        => preg_replace('/[^0-9]/', '', fake()->numerify('(##) #####-####')),
            'appointment_value'   => 150.00,
            'email_verified_at'   => now(),
            'password'            => static::$password ??= Hash::make('password'),
            'year_started_acting' => fake()->year(),
            'crp'                 => fake()->numerify('######'),
            'summary'             => fake()->paragraph(),
            'description'         => fake()->paragraph(),
            'gender_id'           => Gender::inRandomOrder()->first()->id,
            'specialty_id'        => Specialty::inRandomOrder()->first()->id,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function configure()
    {
        return $this->afterCreating(function (Specialist $specialist) {
            $specialist->reasons()->attach(Reason::inRandomOrder()->take(3)->pluck('id'));
        });
    }
}
