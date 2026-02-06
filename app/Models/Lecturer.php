<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'lecturer_code',
        'full_name',
        'date_of_birth',
        'gender',
        'email',
        'phone_number',
        'address',
        'academic_level',
        'position',
        'avatar',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function academicDegrees(): HasMany
    {
        return $this->hasMany(AcademicDegree::class);
    }

    public function workHistories(): HasMany
    {
        return $this->hasMany(WorkHistory::class);
    }

    public function scheduledClasses(): HasMany
    {
    return $this->hasMany(ScheduledClass::class);
    }
      
    public function subject(): BelongsTo
    {
    return $this->belongsTo(Subject::class);
    }
}