<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chapter_id',
        'name',
        'description',
        'display_order',
        'estimated_hours',
        'neet_weightage_percent',
        'difficulty_level',
        'is_active',
    ];

    protected $casts = [
        'neet_weightage_percent' => 'float',
        'display_order'          => 'integer',
        'estimated_hours'        => 'integer',
        'is_active'              => 'boolean',
    ];

    // Relationships

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function subtopics()
    {
        return $this->hasMany(Subtopic::class);
    }

    // Scopes

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order');
    }
}
