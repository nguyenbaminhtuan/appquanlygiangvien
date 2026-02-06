<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'academic_year_id',
        'name',
        'start_date',
        'end_date',
        'is_current',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the academic year that owns the semester.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the scheduled classes for the semester.
     * (Định nghĩa sẵn relationship cho Lớp học phần, sẽ dùng ở bước sau)
     */
    public function scheduledClasses(): HasMany
    {
        return $this->hasMany(ScheduledClass::class); // Giả sử model Lớp học phần tên là ScheduledClass
    }

    /**
     * Scope a query to only include the current semester.
     * (Một scope tiện lợi để lấy kì học hiện tại)
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    // (Tùy chọn) Thêm một model event để xử lý logic is_current
    // protected static function booted()
    // {
    //     static::saving(function ($semester) {
    //         // Nếu is_current được đặt thành true cho kì này
    //         if ($semester->is_current) {
    //             // Đặt tất cả các kì học khác (nếu có) thành is_current = false
    //             static::where('id', '!=', $semester->id)->where('is_current', true)->update(['is_current' => false]);
    //         }
    //     });
    //
    //     // Đảm bảo luôn có một is_current=true nếu có ít nhất một semester,
    //     // hoặc không có is_current=true nào nếu không có semester nào.
    //     // Logic này có thể phức tạp hơn và cần cân nhắc kỹ.
    //     // Có thể xử lý trong controller khi tạo/sửa.
    // }
}