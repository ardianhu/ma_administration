<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use Livewire\Component;

class AcademicYearForm extends Component
{
    public $academicYear;
    public $start;
    public $end;
    public $is_active = true;

    protected $rules = [
        'start' => 'required|date',
        'end' => 'nullable|date|after:start',
        'is_active' => 'boolean',
    ];

    public function mount($academicYear = null)
    {
        if ($academicYear) {
            $this->academicYear = AcademicYear::findOrFail($academicYear);
            $this->start = $this->academicYear->start;
            $this->end = $this->academicYear->end;
            $this->is_active = $this->academicYear->is_active;
        }
    }

    public function save()
    {
        $this->validate();
        $isNew = !$this->academicYear;

        if ($isNew) {
            $this->academicYear = new AcademicYear();
        }

        $this->academicYear->start = $this->start;
        $this->academicYear->end = $this->end;

        if ($this->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        $this->academicYear->is_active = $this->is_active;
        $this->academicYear->save();
        session()->flash('message', $isNew ? 'Tahun ajar berhasil dibuat.' : 'Tahun ajar berhasil diupdate.');
        return redirect()->route('academic-years');
    }

    public function render()
    {
        return view('livewire.academic-year-form');
    }
}
