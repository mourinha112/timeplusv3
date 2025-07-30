<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_id',
        'specialist_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes'
    ];

    /**
     * Relacionamento com o usuário que fez o agendamento
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
