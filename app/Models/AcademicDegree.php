<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicDegree extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'degree_type_id',
        'specialization',
        'issuing_institution',
        'date_issued',
        'notes',
    ];

    // ... (casts) ...

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function degreeType(): BelongsTo
{
    return $this->belongsTo(DegreeType::class);
}
}