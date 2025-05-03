<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $fillable = [
        'nis',
        'name',
        'address',
        'dob',
        'th_child',
        'siblings_count',
        'education',
        'nisn',
        'registration_date',
        'drop_date',
        'drop_reason',
        'father_name',
        'fatehr_dob',
        'father_address',
        'father_phone',
        'father_education',
        'father_job',
        'father_alive',
        'mother_name',
        'mother_dob',
        'mother_address',
        'mother_phone',
        'mother_education',
        'mother_job',
        'mother_alive',
        'guardian_name',
        'guardian_dob',
        'guardian_address',
        'guardian_phone',
        'guardian_education',
        'guardian_job',
        'guardian_relationship',
        'dorm_id',
        'islamic_class_id',
    ];

    public function dorm()
    {
        return $this->belongsTo(Dorm::class);
    }
    public function islamicClass()
    {
        return $this->belongsTo(IslamicClass::class);
    }
    public function permits()
    {
        return $this->hasMany(Permit::class);
    }
}
