<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'notification_type',
        'channel',
        'subject',
        'body',
        'metadata',
        'sent_at',
        'status',
        'provider_response',
    ];

    protected $casts = [
        'metadata'          => 'array',
        'provider_response' => 'array',
        'sent_at'           => 'datetime',
    ];
}
