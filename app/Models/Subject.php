<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'department_id',
        'subject_code',
        'name',
        'credits',
        'default_teaching_hours',
        'subject_coefficient',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'credits' => 'integer',
        'default_teaching_hours' => 'integer', 
        'subject_coefficient' => 'decimal:2',
    ];

    /**
     * Get the department that owns the subject.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the scheduled classes for the subject.
     * (Định nghĩa sẵn relationship cho Lớp học phần, sẽ dùng ở bước sau)
     */
    public function scheduledClasses(): HasMany
    {
        return $this->hasMany(ScheduledClass::class); // Giả sử model Lớp học phần tên là ScheduledClass
    }
}