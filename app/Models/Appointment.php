<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_id',
        'specialist_id',
        'total_value',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'status'            => 'string',
        'total_value'       => 'decimal:2',
    ];

    /**
     * Relacionamento com o usuário que fez o agendamento
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relacionamento com o especialista
     */
    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    /**
     * Scope para buscar agendamentos por data
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('appointment_date', $date);
    }

    /**
     * Scope para buscar agendamentos por período
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('appointment_date', [$startDate, $endDate]);
    }

    /**
     * Scope para agendamentos ativos
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['scheduled', 'confirmed']);
    }
}
