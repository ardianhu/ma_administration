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
        $students = \App\Models\Student::with('permits')
            ->whereNull('drop_date')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        $students->getCollection()->transform(function ($student) {
            $permits = $student->permits;

            if ($permits->every(fn($permit) => $permit->arrive_on !== null)) {
                $student->status = 'no_permit';
            } elseif ($permits->contains(fn($permit) => $permit->arrive_on === null && now()->greaterThan($permit->back_on))) {
                $student->status = 'late';
            } elseif ($permits->contains(fn($permit) => $permit->arrive_on === null)) {
                $student->status = 'have_permit';
            } else {
                $student->status = 'unknown';
            }

            return $student;
        });

        return view('livewire.student-list', [
            'students' => $students,
        ]);
    }
}
