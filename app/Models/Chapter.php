<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject_id',
        'name',
        'code',
        'description',
        'display_order',
        'neet_weightage_percent',
        'ias_weightage_percent',
        'estimated_hours',
        'is_active',
    ];

    protected $casts = [
        'neet_weightage_percent' => 'float',
        'ias_weightage_percent'  => 'float',
        'display_order'          => 'integer',
        'estimated_hours'        => 'integer',
        'is_active'              => 'boolean',
    ];

    // Relationships

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function subtopics()
    {
        return $this->hasManyThrough(Subtopic::class, Topic::class);
    }

    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order');
    }
}
