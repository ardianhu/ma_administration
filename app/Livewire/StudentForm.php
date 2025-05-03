<?php

namespace App\Livewire;

use App\Models\Dorm;
use App\Models\IslamicClass;
use App\Models\Student;
use Carbon\Carbon;
use Livewire\Component;

class StudentForm extends Component
{
    public $student;
    public $nis;
    public $name;
    public $gender = '';
    public $address;
    public $dob;
    public $th_child;
    public $siblings_count;
    public $education;
    public $nisn;
    public $registration_date;
    public $drop_date;
    public $drop_reason;
    public $father_name;
    public $father_dob;
    public $father_address;
    public $father_phone;
    public $father_education;
    public $father_job;
    public $father_alive = '';
    public $mother_name;
    public $mother_dob;
    public $mother_address;
    public $mother_phone;
    public $mother_education;
    public $mother_job;
    public $mother_alive = '';
    public $guardian_name;
    public $guardian_dob;
    public $guardian_address;
    public $guardian_phone;
    public $guardian_education;
    public $guardian_job;
    public $guardian_relationship;
    public $dorm_id = '';
    public $islamic_class_id = '';

    protected $rules = [
        'nis' => 'nullable|integer|unique:students,nis',
        'name' => 'required|string|max:255',
        'gender' => 'required|in:L,P',
        'address' => 'nullable|string|max:255',
        'dob' => 'nullable|string|max:255',
        'th_child' => 'nullable|integer',
        'siblings_count' => 'nullable|integer',
        'education' => 'nullable|string|max:255',
        'nisn' => 'nullable|integer|unique:students,nisn',
        'registration_date' => 'nullable',
        'drop_date' => 'nullable|date',
        'drop_reason' => 'nullable|string|max:255',
        'father_name' => 'nullable|string|max:255',
        'father_dob' => 'nullable|string|max:255',
        'father_address' => 'nullable|string|max:255',
        'father_phone' => 'nullable|string|max:255',
        'father_education' => 'nullable|string|max:255',
        'father_job' => 'nullable|string|max:255',
        'father_alive' => 'nullable|in:Hidup,Meninggal',
        'mother_name' => 'nullable|string|max:255',
        'mother_dob' => 'nullable|string|max:255',
        'mother_address' => 'nullable|string|max:255',
        'mother_phone' => 'nullable|string|max:255',
        'mother_education' => 'nullable|string|max:255',
        'mother_job' => 'nullable|string|max:255',
        'mother_alive' => 'nullable|in:Hidup,Meninggal',
        'guardian_name' => 'nullable|string|max:255',
        'guardian_dob' => 'nullable|string|max:255',
        'guardian_address' => 'nullable|string|max:255',
        'guardian_phone' => 'nullable|string|max:255',
        'guardian_education' => 'nullable|string|max:255',
        'guardian_job' => 'nullable|string|max:255',
        'guardian_relationship' => 'nullable|string|max:255',
        'dorm_id' => 'nullable|exists:dorms,id',
        'islamic_class_id' => 'nullable|exists:islamic_classes,id',
    ];

    public function mount($student = null)
    {
        if ($student) {
            $studentModel = Student::findOrFail($student);
            $this->student = $studentModel;

            $this->nis = $studentModel->nis;
            $this->name = $studentModel->name;
            $this->gender = $studentModel->gender;
            $this->address = $studentModel->address;
            $this->dob = $studentModel->dob;
            $this->th_child = $studentModel->th_child;
            $this->siblings_count = $studentModel->siblings_count;
            $this->education = $studentModel->education;
            $this->nisn = $studentModel->nisn;
            $this->registration_date = $studentModel->registration_date;
            $this->drop_date = $studentModel->drop_date;
            $this->drop_reason = $studentModel->drop_reason;
            $this->father_name = $studentModel->father_name;
            $this->father_dob = $studentModel->father_dob;
            $this->father_address = $studentModel->father_address;
            $this->father_phone = $studentModel->father_phone;
            $this->father_education = $studentModel->father_education;
            $this->father_job = $studentModel->father_job;
            $this->father_alive = ($studentModel->father_alive == null) ? '' : $studentModel->father_alive;
            $this->mother_name = $studentModel->mother_name;
            $this->mother_dob = $studentModel->mother_dob;
            $this->mother_address = $studentModel->mother_address;
            $this->mother_phone = $studentModel->mother_phone;
            $this->mother_education = $studentModel->mother_education;
            $this->mother_job = $studentModel->mother_job;
            $this->mother_alive = ($studentModel->mother_alive == null) ? '' : $studentModel->mother_alive;
            $this->guardian_name = $studentModel->guardian_name;
            $this->guardian_dob = $studentModel->guardian_dob;
            $this->guardian_address = $studentModel->guardian_address;
            $this->guardian_phone = $studentModel->guardian_phone;
            $this->guardian_education = $studentModel->guardian_education;
            $this->guardian_job = $studentModel->guardian_job;
            $this->guardian_relationship = $studentModel->guardian_relationship;
            $this->dorm_id = ($studentModel->dorm_id == null) ? '' : $studentModel->dorm_id;
            $this->islamic_class_id = ($studentModel->islamic_class_id == null) ? '' : $studentModel->islamic_class_id;
        }
    }

    public function save()
    {
        // dd($this->dorm_id);
        $this->validate();
        $isNew = !$this->student;

        if ($isNew) {
            $this->student = new Student();
        }

        $this->student->nis = $this->nis;
        $this->student->name = $this->name;
        $this->student->gender = $this->gender;
        $this->student->address = $this->address;
        $this->student->dob = $this->dob;
        $this->student->th_child = $this->th_child;
        $this->student->siblings_count = $this->siblings_count;
        $this->student->education = $this->education;
        $this->student->nisn = $this->nisn;
        if ($this->registration_date) {
            $this->student->registration_date = $this->registration_date;
        } else {
            $this->student->registration_date = Carbon::now()->format('Y-m-d');
        }
        $this->student->drop_date = $this->drop_date;
        $this->student->drop_reason = $this->drop_reason;
        $this->student->father_name = $this->father_name;
        $this->student->father_dob = $this->father_dob;
        $this->student->father_address = $this->father_address;
        $this->student->father_phone = $this->father_phone;
        $this->student->father_education = $this->father_education;
        $this->student->father_job = $this->father_job;
        $this->student->father_alive = ($this->father_alive == '') ? null : $this->father_alive;
        $this->student->mother_name = $this->mother_name;
        $this->student->mother_dob = $this->mother_dob;
        $this->student->mother_address = $this->mother_address;
        $this->student->mother_phone = $this->mother_phone;
        $this->student->mother_education = $this->mother_education;
        $this->student->mother_job = $this->mother_job;
        $this->student->mother_alive = ($this->mother_alive == '') ? null : $this->mother_alive;
        $this->student->guardian_name = $this->guardian_name;
        $this->student->guardian_dob = $this->guardian_dob;
        $this->student->guardian_address = $this->guardian_address;
        $this->student->guardian_phone = $this->guardian_phone;
        $this->student->guardian_education = $this->guardian_education;
        $this->student->guardian_job = $this->guardian_job;
        $this->student->guardian_relationship = $this->guardian_relationship;
        $this->student->dorm_id = $this->dorm_id ? $this->dorm_id : null;
        $this->student->islamic_class_id = $this->islamic_class_id ? $this->islamic_class_id : null;
        $this->student->save();
        session()->flash('message', $isNew ? 'Santri berhasil dibuat.' : 'Santri berhasil diupdate.');
        return redirect()->route('students');
    }

    public function render()
    {
        $dorms = Dorm::all();
        $islamicClasses = IslamicClass::all();
        return view('livewire.student-form', [
            'dorms' => $dorms,
            'islamicClasses' => $islamicClasses,
        ]);
    }
}
