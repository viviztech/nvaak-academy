<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'course_type',
        'name',
        'code',
        'description',
        'color_code',
        'icon',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'display_order' => 'integer',
    ];

    // Relationships

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function topics()
    {
        return $this->hasManyThrough(Topic::class, Chapter::class);
    }

    // Scopes

    public function scopeNeet(Builder $query): Builder
    {
        return $query->whereIn('course_type', ['neet', 'both']);
    }

    public function scopeIas(Builder $query): Builder
    {
        return $query->whereIn('course_type', ['ias', 'both']);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
