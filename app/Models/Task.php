<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nome',
        'descricao',
        'finalizado',
        'data_limite',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'finalizado' => 'boolean',
        'data_limite' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Scope to get only non-deleted tasks
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope to get only completed tasks
     */
    public function scopeCompleted($query)
    {
        return $query->where('finalizado', true);
    }

    /**
     * Scope to get only pending tasks
     */
    public function scopePending($query)
    {
        return $query->where('finalizado', false);
    }

    /**
     * Get the formatted deadline date
     */
    public function getFormattedDeadlineAttribute(): ?string
    {
        if (!$this->data_limite) {
            return null;
        }

        $now = now();
        $deadline = $this->data_limite;

        if ($deadline->isToday()) {
            return 'Hoje';
        }

        if ($deadline->isTomorrow()) {
            return 'Amanhã';
        }

        return $deadline->format('l, d/m/Y');
    }

    /**
     * Check if task is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->data_limite || $this->finalizado) {
            return false;
        }

        return $this->data_limite->isPast();
    }
} 