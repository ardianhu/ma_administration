<?php

namespace App\Livewire;

use App\Models\IslamicClass;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class IslamicClassMember extends Component
{
    use WithPagination;
    public $search = '';

    public $islamic_class;
    public $islamic_class_members;

    public $selected = 0;

    public function mount($islamic_class = null)
    {
        $this->islamic_class = IslamicClass::findOrFail($islamic_class);
        $this->search = '';
    }

    public function updateIslamicClassMembers()
    {
        $this->islamic_class_members = Student::whereHas('islamicClass', function ($query) {
            $query->where('islamic_class_id', $this->islamic_class->id);
        })->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateSelected($studentId)
    {
        $this->selected = $studentId;
    }

    public function removeMember()
    {
        $student = Student::find($this->selected);
        if ($student) {
            $student->islamic_class_id = null;
            $student->save();
            $this->selected = 0;
            session()->flash('message', 'Santri berhasil dikeluarkan.');
            return redirect()->route('class.member', $this->islamic_class->id);
        } else {
            session()->flash('error', 'Santri tidak ditemukan.');
        }
    }

    public function addMember()
    {
        $student = Student::find($this->selected);
        if ($student) {
            $student->islamic_class_id = $this->islamic_class->id;
            $student->save();
            $this->selected = 0;
            session()->flash('message', 'Santri berhasil dimasukkan.');
            return redirect()->route('class.member', $this->islamic_class->id);
        } else {
            session()->flash('error', 'Santri tidak ditemukan.');
        }
    }

    public function render()
    {
        $students = Student::where('name', 'like', '%' . $this->search . '%')
            ->whereNull('drop_date')
            ->orderBy('name', 'asc')
            ->get();
        $this->updateIslamicClassMembers();
        return view('livewire.islamic-class-member', [
            'students' => $students,
        ]);
    }
}
