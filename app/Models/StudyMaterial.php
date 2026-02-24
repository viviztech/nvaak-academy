<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'batch_id',
        'subject_id',
        'chapter_id',
        'topic_id',
        'title',
        'description',
        'material_type',
        'file_path',
        'external_url',
        'file_size_kb',
        'duration_minutes',
        'is_free_preview',
        'access_type',
        'thumbnail_path',
        'tags',
        'download_count',
        'view_count',
        'is_published',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'tags'            => 'array',
        'is_published'    => 'boolean',
        'is_free_preview' => 'boolean',
        'published_at'    => 'datetime',
    ];

    // Relationships

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accessLogs()
    {
        return $this->hasMany(MaterialAccessLog::class, 'material_id');
    }

    // Scopes

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeForBatch($query, int $batchId)
    {
        return $query->where(function ($q) use ($batchId) {
            $q->where('batch_id', $batchId)
              ->orWhere('access_type', 'all');
        });
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('material_type', $type);
    }

    // Accessors

    public function getFileSizeMbAttribute(): ?float
    {
        return $this->file_size_kb ? round($this->file_size_kb / 1024, 2) : null;
    }
}
