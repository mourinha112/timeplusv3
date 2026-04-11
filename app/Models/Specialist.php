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
     * Valor fixo recebido pelo especialista por sessão TimePlus (em reais).
     */
    public const TIMEPLUS_SESSION_VALUE = 30.00;

    /**
     * Valor padrão da sessão particular quando o especialista não definiu o seu.
     */
    public const DEFAULT_APPOINTMENT_VALUE = 30.00;

    public const MIN_SESSION_DURATION = 15;
    public const MAX_SESSION_DURATION = 50;
    public const DEFAULT_SESSION_DURATION = 30;

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
        'particular_session_value',
        'session_duration_minutes',
        'accepts_timeplus',
        'accepts_particular',
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
            'email_verified_at'         => 'datetime',
            'password'                  => 'hashed',
            'accepts_timeplus'          => 'boolean',
            'accepts_particular'        => 'boolean',
            'session_duration_minutes'  => 'integer',
            'particular_session_value'  => 'decimal:2',
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
     * Valor da sessão particular do especialista. Cai pro default só se nada
     * estiver definido. O valor da sessão TimePlus é fixo em TIMEPLUS_SESSION_VALUE.
     */
    public function getAppointmentValueAttribute($value)
    {
        $particular = $this->attributes['particular_session_value'] ?? null;

        return $particular !== null
            ? (float) $particular
            : ((float) ($value ?? self::DEFAULT_APPOINTMENT_VALUE));
    }

    /**
     * Retorna o valor a cobrar do paciente conforme o modo de atendimento.
     */
    public function valueForMode(string $mode): float
    {
        return $mode === 'timeplus'
            ? self::TIMEPLUS_SESSION_VALUE
            : (float) $this->appointment_value;
    }

    /**
     * Duração da sessão em minutos, dentro dos limites permitidos.
     */
    public function getSessionDuration(): int
    {
        $duration = (int) ($this->attributes['session_duration_minutes'] ?? self::DEFAULT_SESSION_DURATION);

        return max(self::MIN_SESSION_DURATION, min(self::MAX_SESSION_DURATION, $duration));
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
