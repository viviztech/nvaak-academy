<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'title',
        'body',
        'target_audience',
        'target_batch_ids',
        'is_pinned',
        'published_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'target_batch_ids' => 'array',
        'is_pinned'        => 'boolean',
        'published_at'     => 'datetime',
        'expires_at'       => 'datetime',
    ];

    // Relationships

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reads()
    {
        return $this->hasMany(AnnouncementRead::class);
    }

    // Scopes

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            });
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
}
