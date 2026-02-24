<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryFollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'followed_by',
        'follow_up_type',
        'notes',
        'outcome',
        'next_follow_up_at',
        'is_converted',
    ];

    protected $casts = [
        'is_converted'    => 'boolean',
        'next_follow_up_at' => 'datetime',
    ];

    // Relationships

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function followedBy()
    {
        return $this->belongsTo(User::class, 'followed_by');
    }
}
