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
        $this->studentsCount = Student::where('drop_date', null)->count();
        $this->maleStudentsCount = Student::where('gender', 'L')->where('drop_date', null)->count();
        $this->femaleStudentsCount = Student::where('gender', 'P')->where('drop_date', null)->count();
        return view('livewire.dashboard');
    }
}
