<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IslamicClass extends Model
{
    //
    protected $fillable = [
        'name',
        'iteration',
    ];
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
