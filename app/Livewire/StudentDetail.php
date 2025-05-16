<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Dorm;
use App\Models\IslamicClass;
use App\Models\Permit;
use App\Models\Student;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class StudentDetail extends Component
{
    public $student;
    public $heatmap = null;
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

            $today = now()->startOfDay();

            // get all the dates between the start and end of the academic year
            $academicYear = AcademicYear::where('is_active', true)->first();
            $period = CarbonPeriod::create($academicYear->start, $academicYear->end);
            $dates = collect($period)->map(fn($date) => $date->format('Y-m-d'));

            // get the students permit for the current academic year
            $permits = Permit::where('student_id', $studentModel->id)
                ->where('academic_year_id', $academicYear->id)
                ->get();

            // create a daily status of array
            $this->heatmap = $dates->mapWithKeys(function ($date) use ($today) {
                return [$date => Carbon::parse($date)->gt($today) || Carbon::parse($date)->lt($this->registration_date) ? 'gray' : 'green'];
            });

            foreach ($permits as $permit) {
                $leaveDate = Carbon::parse($permit->leave_on);
                $backDate = Carbon::parse($permit->back_on);
                $arriveDate = $permit->arrive_on ? Carbon::parse($permit->arrive_on) : null;

                // mark yellow during permit
                $leavePeriod = CarbonPeriod::create($leaveDate, $backDate);
                foreach ($leavePeriod as $day) {
                    $dayStr = $day->format('Y-m-d');
                    if (isset($this->heatmap[$dayStr])) {
                        $this->heatmap[$dayStr] = 'yellow';
                    }
                }

                // mark red if returned late or hasn't returned yet
                if (($arriveDate && $arriveDate->gt($backDate)) || (!$arriveDate && $backDate->lt($today))) {
                    $lateEnd = $arriveDate ?? $today->copy()->addDay(); // include today
                    $latePeriod = CarbonPeriod::create($backDate->copy()->addDay(), $lateEnd);
                    foreach ($latePeriod as $day) {
                        $dayStr = $day->format('Y-m-d');
                        if (isset($this->heatmap[$dayStr])) {
                            $this->heatmap[$dayStr] = 'red';
                        }
                    }
                }
            }
        }
    }

    public function render()
    {
        $dorms = Dorm::all();
        $islamicClasses = IslamicClass::all();
        return view('livewire.student-detail', [
            'dorms' => $dorms,
            'islamicClasses' => $islamicClasses,
        ]);
    }


    public function deleteStudent()
    {
        Permit::where('student_id', $this->student->id)->delete();
        $this->student->delete();
        session()->flash('message', 'Santri berhasil dihapus.');
        return redirect()->route('students');
    }

    public function dropStudent()
    {
        Student::where('id', $this->student->id)->update([
            'drop_date' => $this->drop_date,
            'drop_reason' => $this->drop_reason,
            'dorm_id' => null,
            'islamic_class_id' => null
        ]);
        Permit::where('student_id', $this->student->id)->delete();
        session()->flash('message', 'Santri berhasil dikeluarkan.');
        return redirect()->route('students');
    }
}
