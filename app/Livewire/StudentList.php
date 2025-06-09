<?php

namespace App\Livewire;

use App\Exports\StudentsExport;
use App\Imports\StudentImport;
use App\Models\Dorm;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class StudentList extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $file;

    public $dorm_id = '';

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,xls'
    ];

    public function uploadExcel()
    {
        $this->validate();
        Excel::import(new StudentImport($this->dorm_id), $this->file->getRealPath());
        $this->file = null;
        session()->flash('message', 'Data santri berhasil diupload.');
        return redirect()->route('students');
    }

    public function downloadExcel()
    {
        return Excel::download(new StudentsExport(), 'data_santri.xlsx');
    }

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (request()->has('dashboard_search')) {
            $this->search = request()->query('dashboard_search');
        }

        if (request()->routeIs('students.alumni')) {
            $students = \App\Models\Student::with('permits')
                ->whereNotNull('drop_date')
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%');
                })
                ->orderBy('name', 'asc')
                ->paginate(10);
        } else {
            $students = \App\Models\Student::with('permits')
                ->whereNull('drop_date')
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%');
                })
                ->orderBy('name', 'asc')
                ->paginate(10);
        }

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

            $violations = $student->violations;
            if (
                $violations->isNotEmpty() &&
                $violations->every(
                    fn($violation) =>
                    $violation->violation_date !== null &&
                        $violation->resolved_at == null &&
                        $violation->violation_type === 'pulang'
                )
            ) {
                $student->status = 'leave_not_returned';
            }

            return $student;
        });

        // dd($students);

        $dorms = Dorm::all();

        return view('livewire.student-list', [
            'students' => $students,
            'dorms' => $dorms,
        ]);
    }
}
