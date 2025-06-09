<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    //
    protected $fillable = [
        'student_id',
        'violation_type',
        'violation_description',
        'violation_date',
        'resolved_at',
        'academic_year_id',
    ];
    protected $casts = [
        'violation_date' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
