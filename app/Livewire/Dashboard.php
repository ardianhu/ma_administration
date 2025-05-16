<?php

namespace App\Livewire;

use App\Models\Student;
use Livewire\Component;

class Dashboard extends Component
{
    public $studentsCount;
    public $maleStudentsCount;
    public $femaleStudentsCount;

    public function render()
    {
        $counts = Student::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN gender = 'L' THEN 1 ELSE 0 END) as male,
            SUM(CASE WHEN gender = 'P' THEN 1 ELSE 0 END) as female
        ")->whereNull('drop_date')->first();

        $this->studentsCount = $counts->total;
        $this->maleStudentsCount = $counts->male;
        $this->femaleStudentsCount = $counts->female;

        return view('livewire.dashboard');
    }
}
