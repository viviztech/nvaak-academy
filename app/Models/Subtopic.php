<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subtopic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'topic_id',
        'name',
        'description',
        'display_order',
        'key_concepts',
        'is_active',
    ];

    protected $casts = [
        'key_concepts'  => 'array',
        'display_order' => 'integer',
        'is_active'     => 'boolean',
    ];

    // Relationships

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
