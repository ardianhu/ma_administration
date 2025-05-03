<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $students = \App\Models\Student::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('nisn', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(10);
        return view('livewire.student-list', [
            'students' => $students,
        ]);
    }
}
