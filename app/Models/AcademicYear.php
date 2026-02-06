<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Sẽ dùng cho Kì học sau này

class AcademicYear extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date', // Tự động cast thành đối tượng Carbon
        'end_date' => 'date',   // Tự động cast thành đối tượng Carbon
    ];

    /**
     * Get the semesters for the academic year.
     * (Định nghĩa sẵn relationship cho Kì học, sẽ dùng ở bước sau)
     */
    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class); // Giả sử model Kì học tên là Semester
    }
}