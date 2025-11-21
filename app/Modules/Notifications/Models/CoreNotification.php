<?php

namespace App\Modules\Notifications\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreNotification extends Model
{
    protected $table = 'core_notifications';

    protected $fillable = [
        'module',       // 'bank-manager', 'pomodoro', etc.
        'type',         // 'reminder', 'warning', 'info', etc.
        'context',      // 'missing_expenses', 'debt_overdue', ...
        'title',
        'message',
        'status',       // 'active', 'checked', 'ignored'
        'meta',         // json nullable
        'triggered_at',
        'resolved_at',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'resolved_at'  => 'datetime',
    ];

    public function markChecked(): void
    {
        $this->update([
            'status'      => 'checked',
            'resolved_at' => now(),
        ]);
    }

    public function markIgnored(): void
    {
        $this->update([
            'status'      => 'ignored',
            'resolved_at' => now(),
        ]);
    }
    
}
