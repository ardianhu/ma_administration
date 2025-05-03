<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use Livewire\Component;
use Livewire\WithPagination;

class AcademicYearList extends Component
{
    use WithPagination;

    public $selected = 0;

    public function render()
    {
        $academicYears = AcademicYear::orderBy('start', 'desc')
            ->paginate(10);
        return view('livewire.academic-year-list', [
            'academicYears' => $academicYears,
        ]);
    }

    public function deleteSelected($itemSelected)
    {
        $this->selected = $itemSelected;
    }

    public function deleteAcademicYear()
    {
        $academicYear = AcademicYear::find($this->selected);
        if ($academicYear->is_active == true) {
            session()->flash('error', 'Tahun ajaran aktif tidak bisa dihapus.');
            return redirect()->route('academic-years');
        } elseif ($academicYear) {
            $academicYear->delete();
            $this->selected = 0;
            session()->flash('message', 'Tahun ajaran berhasil dihapus.');
            return redirect()->route('academic-years');
        } else {
            session()->flash('error', 'Tahun ajaran tidak ditemukan.');
        }
    }
}
