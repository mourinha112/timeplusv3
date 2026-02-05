<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Specialist extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * Valor padrão da sessão (em reais)
     * Usado quando o especialista não tem valor definido ou para padronização
     */
    public const DEFAULT_APPOINTMENT_VALUE = 30.00;

    protected $fillable = [
        'gender_id',
        'specialty_id',
        'avatar',
        'name',
        'cpf',
        'phone_number',
        'email',
        'password',
        'crp',
        'birth_date',
        'state_id',
        'appointment_value',
        'lgbtqia',
        'summary',
        'description',
        'year_started_acting',
        'onboarding_step',
        'is_active',
        'email_verified_at',
        'remember_token',
        'recovery_password_token',
        'recovery_password_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'recovery_password_token',
        'recovery_password_token_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function setBirthDateAttribute($value)
    {
        if ($value) {
            // Converte DD/MM/AAAA para AAAA-MM-DD
            $date                           = \Carbon\Carbon::createFromFormat('d/m/Y', $value);
            $this->attributes['birth_date'] = $date->format('Y-m-d');
        }
    }

    public function getBirthDateAttribute($value)
    {
        if ($value) {
            // Converte AAAA-MM-DD para DD/MM/AAAA para exibição
            return \Carbon\Carbon::parse($value)->format('d/m/Y');
        }

        return $value;
    }

    /**
     * Retorna o valor da sessão padronizado
     * Se o especialista tiver valor definido, usa o dele.
     * Caso contrário, usa o valor padrão da plataforma (R$ 30,00)
     */
    public function getAppointmentValueAttribute($value)
    {
        // Força o valor padrão para todos (padronização solicitada)
        return self::DEFAULT_APPOINTMENT_VALUE;

        // Se quiser usar o valor individual do especialista quando definido, descomente:
        // return $value ?? self::DEFAULT_APPOINTMENT_VALUE;
    }

    /**
     * Relationships
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function reasons(): BelongsToMany
    {
        return $this->belongsToMany(Reason::class, 'reason_specialists');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function paymentProfile(): HasOne
    {
        return $this->hasOne(SpecialistPaymentProfile::class);
    }

    /**
     * Verifica se o especialista tem dados de pagamento cadastrados
     */
    public function hasPaymentProfile(): bool
    {
        return $this->paymentProfile()->exists();
    }
}
