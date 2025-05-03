<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    //
    protected $fillable = [
        'user_id',
        'student_id',
        'leave_on',
        'back_on',
        'reason',
        'destination',
        'permit_type',
        'extended_count',
        'academic_year_id',
    ];
    protected $casts = [
        'leave_on' => 'datetime',
        'back_on' => 'datetime',
        'arrive_on' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
