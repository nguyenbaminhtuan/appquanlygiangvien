<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester_id',
        'subject_id',
        'class_code',
        'lecturer_id',
        'max_students',
        'current_students', // Giữ lại nếu vẫn dùng, hoặc có thể gộp/thay thế bằng actual_students
        'actual_students',        // <<--- THÊM DÒNG NÀY
        'actual_teaching_hours',  // <<--- THÊM DÒNG NÀY
        'schedule_info',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'max_students' => 'integer',
        'current_students' => 'integer',
        'actual_students' => 'integer',          // <<--- THÊM DÒNG NÀY
        'actual_teaching_hours' => 'integer',    // <<--- THÊM DÒNG NÀY
    ];

    public function semester(): BelongsTo { return $this->belongsTo(Semester::class); }
    public function subject(): BelongsTo { return $this->belongsTo(Subject::class); }
    public function lecturer(): BelongsTo { return $this->belongsTo(Lecturer::class); }
}