<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    //
    protected $fillable = [
        'start',
        'end',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function permits()
    {
        return $this->hasMany(Permit::class);
    }
}
