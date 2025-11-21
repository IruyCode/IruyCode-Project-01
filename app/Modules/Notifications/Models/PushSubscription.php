<?php

namespace App\Modules\Notifications\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    use Hasfactory;

    protected $table = 'push_subscriptions';

    protected $fillable = [
        'user_id',
        'subscription',
    ];

    protected $casts = [
        'subscription' => 'array',
    ];
}
