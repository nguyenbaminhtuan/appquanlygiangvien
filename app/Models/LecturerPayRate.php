<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerPayRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_level_or_title',
        'coefficient',
        'effective_date',
        'notes',
    ];

    protected $casts = [
        'coefficient' => 'decimal:2',
        'effective_date' => 'date',
    ];
}