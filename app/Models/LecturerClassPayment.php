<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LecturerClassPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'lecturer_id', 'scheduled_class_id', 'semester_id', 'academic_year_id',
        'actual_teaching_hours_snapshot', 'subject_coefficient_snapshot',
        'class_size_coefficient_snapshot', 'lecturer_coefficient_snapshot',
        'base_rate_snapshot', 'converted_teaching_units', 'payment_amount',
        'calculation_date', 'status', 'notes',
    ];

    protected $casts = [
        'actual_teaching_hours_snapshot' => 'integer',
        'subject_coefficient_snapshot' => 'decimal:2',
        'class_size_coefficient_snapshot' => 'decimal:2',
        'lecturer_coefficient_snapshot' => 'decimal:2',
        'base_rate_snapshot' => 'decimal:2',
        'converted_teaching_units' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'calculation_date' => 'datetime',
    ];

    public function lecturer(): BelongsTo { return $this->belongsTo(Lecturer::class); }
    public function scheduledClass(): BelongsTo { return $this->belongsTo(ScheduledClass::class); }
    public function semester(): BelongsTo { return $this->belongsTo(Semester::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
}