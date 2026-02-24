<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_id',
        'document_type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'is_verified',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // Relationships

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
