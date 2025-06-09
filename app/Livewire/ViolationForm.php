<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Violation;
use Livewire\Component;
use Livewire\WithPagination;

class ViolationForm extends Component
{
    use WithPagination;
    public $search = '';

    public $violation;

    public $selectedStudent;

    public $student_id = '';
    public $violation_type = '';
    public $violation_date;
    public $resolved_at;
    public $violation_description;

    protected $rules = [
        'student_id' => 'required|exists:students,id',
        'violation_type' => 'required',
        'violation_date' => 'required|date',
        'resolved_at' => 'nullable|date|after:violation_date',
        'violation_description' => 'nullable|string|max:255',
    ];

    public function mount($violation = null)
    {
        if ($violation) {
            $this->violation = Violation::findOrFail($violation);
            $this->student_id = $this->violation->student_id;
            $this->violation_type = $this->violation->violation_type;
            $this->violation_date = $this->violation->violation_date;
            $this->resolved_at = $this->violation->resolved_at;
            $this->violation_description = $this->violation->violation_description;
        } else {
            // Initialize student if there's query
            if (request()->has('student')) {
                $student = request()->get('student');
                $this->selectedStudent = Student::findOrFail($student);
                $this->student_id = $this->selectedStudent->id;
            }
        }
    }

    public function updateSelectedStudent($studentId)
    {
        $student = Student::findOrFail($studentId);
        $this->selectedStudent = $student;
        $this->student_id = $this->selectedStudent->id;
        $this->search = '';
    }

    public function save()
    {
        $this->validate();

        $isNew = !$this->violation;

        if ($isNew) {
            $this->violation = new Violation();
            $this->violation->academic_year_id = AcademicYear::where('is_active', true)->first()->id;
        }

        $this->violation->student_id = $this->student_id;
        $this->violation->violation_type = $this->violation_type;
        $this->violation->violation_date = $this->violation_date;
        $this->violation->resolved_at = $this->resolved_at;
        $this->violation->violation_description = $this->violation_description;
        $this->violation->save();

        session()->flash('message', 'Pelanggaran berhasil dibuat.');
        return redirect()->route('violations');
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
        return view('livewire.violation-form', [
            'students' => $students,
            'selectedStudent' => $this->selectedStudent,
        ]);
    }
}
