<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Permit;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PermitForm extends Component
{
    use WithPagination;
    public $search = '';

    public $permit;

    public $selectedStudent;

    public $student_id = '';
    public $user_id = '';
    public $permit_type = '';
    public $leave_on;
    public $back_on;
    public $reason;
    public $destination;

    protected $rules = [
        'student_id' => 'required|exists:students,id',
        'user_id' => 'required|exists:users,id',
        'permit_type' => 'required|in:sakit,kepentingan',
        'leave_on' => 'required|date',
        'back_on' => 'required|date|after:leave_on',
        'reason' => 'nullable|string|max:255',
        'destination' => 'required|string|max:255',
    ];

    public function mount($permit = null)
    {
        $this->user_id = Auth::id();
        if ($permit) {
            $this->permit = Permit::findOrFail($permit);
            $this->student_id = $this->permit->student_id;
            $this->user_id = $this->permit->user_id;
            $this->permit_type = $this->permit->permit_type;
            $this->leave_on = $this->permit->leave_on;
            $this->back_on = $this->permit->back_on;
            $this->reason = $this->permit->reason;
            $this->destination = $this->permit->destination;
        } else {
            if (Auth::user()->role->name == 'keamanan') {
                $this->permit_type = 'kepentingan';
            } elseif (Auth::user()->role->name == 'kesehatan') {
                $this->permit_type = 'sakit';
            } else {
                $this->permit_type = '';
            }
            // $student = Student
            if (request()->has('student')) {
                $student = request()->get('student');
                $this->selectedStudent = Student::findOrFail($student);
                $this->student_id = $this->selectedStudent->id;
            }
        }
        // dd($this->permit_type);
    }

    public function updateSelectedStudent($studentId)
    {
        $student = Student::find($studentId);
        $this->selectedStudent = $student;
        $this->student_id = $this->selectedStudent->id;
        $this->search = '';
    }

    public function save()
    {
        $this->validate();
        $isNew = !$this->permit;

        if ($isNew) {
            $this->permit = new Permit();
            $this->permit->academic_year_id = AcademicYear::where('is_active', true)->first()->id;
        }

        $this->permit->student_id = $this->student_id;
        $this->permit->user_id = $this->user_id;
        $this->permit->leave_on = $this->leave_on;
        $this->permit->permit_type = $this->permit_type;
        $this->permit->back_on = $this->back_on;
        $this->permit->reason = $this->reason;
        $this->permit->destination = $this->destination;

        $this->permit->save();

        session()->flash('message', $isNew ? 'Izin berhasil dibuat.' : 'Izin berhasil diupdate.');
        return redirect()->route('permits');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $students = Student::whereNull('drop_date')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nis', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name', 'asc')
            ->get();
        return view('livewire.permit-form', [
            'students' => $students,
            'selectedStudent' => $this->selectedStudent,
        ]);
    }
}
