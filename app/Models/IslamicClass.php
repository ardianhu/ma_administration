<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IslamicClass extends Model
{
    //
    protected $fillable = [
        'name',
        'class',
        'sub_class',
    ];
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
