<?php

namespace App\Livewire;

use App\Exports\DormMembersExport;
use App\Models\Dorm;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class DormMember extends Component
{
    use WithPagination;
    public $search = '';

    public $dorm;
    public $dorm_members;

    public $selected = 0;

    public function mount($dorm = null)
    {
        $this->dorm = Dorm::findOrFail($dorm);
        $this->search = '';
    }

    public function updateDormMembers()
    {
        $this->dorm_members = Student::whereHas('dorm', function ($query) {
            $query->where('dorm_id', $this->dorm->id);
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

    public function removeMmeber()
    {
        $student = Student::find($this->selected);
        if ($student) {
            $student->dorm_id = null;
            $student->save();
            $this->selected = 0;
            session()->flash('message', 'Anggota asrama berhasil dihapus.');
            return redirect()->route('dorms.member', $this->dorm->id);
        } else {
            session()->flash('error', 'Anggota asrama tidak ditemukan.');
        }
    }

    public function addMember()
    {
        $student = Student::find($this->selected);
        if ($student) {
            $student->dorm_id = $this->dorm->id;
            $student->save();
            $this->selected = 0;
            session()->flash('message', 'Anggota asrama berhasil ditambahkan.');
            return redirect()->route('dorms.member', $this->dorm->id);
        } else {
            session()->flash('error', 'Santri tidak ditemukan.');
        }
    }

    public function exportMembers()
    {
        return Excel::download(new DormMembersExport($this->dorm->id), 'anggota_asrama_' . $this->dorm->block . '-' . $this->dorm->room_number . '.xlsx');
    }

    public function render()
    {
        if ($this->dorm->zone == 'putra') {
            $students = Student::where('name', 'like', '%' . $this->search . '%')
                ->where('gender', 'L')
                ->whereNull('drop_date')
                ->orderBy('name', 'asc')
                ->get();
        }
        if ($this->dorm->zone == 'putri') {
            $students = Student::where('name', 'like', '%' . $this->search . '%')
                ->where('gender', 'P')
                ->whereNull('drop_date')
                ->orderBy('name', 'asc')
                ->get();
        }
        $this->updateDormMembers();
        return view('livewire.dorm-member', [
            'students' => $students,
        ]);
    }
}
