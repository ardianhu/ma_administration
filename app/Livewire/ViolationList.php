<?php

namespace App\Livewire;

use App\Exports\SimpleViolationExport;
use App\Exports\ViolationExport;
use App\Models\Dorm;
use App\Models\Violation;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ViolationList extends Component
{
    use WithPagination;

    public $search = '';
    public $selected = 0;

    public $exportStartDate;
    public $exportEndDate;

    public $dorm_id = '';
    public $download_type = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Violation::whereHas('academicYear', function ($query) {
            $query->where('is_active', true);
        })
            ->whereHas('student', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });

        if (request()->has('type')) {
            $type = request()->get('type');
            if ($type == 'leave') {
                $query->where('violation_type', 'pulang');
            } elseif ($type == 'leave_not_returned') {
                $query->where('violation_type', 'pulang')
                    ->where('resolved_at', null);
            } elseif ($type == 'others') {
                $query->where('violation_type', 'lainnya');
            }
        }
        $violations = $query->orderBy('violation_date', 'desc')->paginate(10);

        $dorm = Dorm::all();

        return view('livewire.violation-list', [
            'violations' => $violations,
            'dorms' => $dorm,
        ]);
    }

    public function deleteSelected($itemSelected)
    {
        $this->selected = $itemSelected;
    }
    public function violationSelected($itemSelected)
    {
        $this->selected = $itemSelected;
    }
    public function deleteViolation()
    {
        $violation = Violation::find($this->selected);
        if ($violation) {
            $violation->delete();
            $this->selected = 0;
            session()->flash('message', 'Pelanggaran berhasil dihapus.');
            return redirect()->route('violations');
        } else {
            session()->flash('error', 'Pelanggaran tidak ditemukan.');
        }
    }

    public function arrivalConfirm()
    {
        $violation = Violation::find($this->selected);
        if ($violation) {
            $violation->resolved_at = Carbon::now();
            $violation->save();
            $this->selected = 0;
            session()->flash('message', 'Pelanggaran berhasil diupdate.');
            return redirect()->route('violations');
        } else {
            session()->flash('error', 'Pelanggaran tidak ditemukan.');
        }
    }

    public function downloadViolation()
    {
        if ($this->download_type == 'accumulation') {
            return Excel::download(new SimpleViolationExport($this->exportStartDate, $this->exportEndDate, $this->dorm_id), 'akumulasi_pelanggaran.xlsx');
        } elseif ($this->download_type == 'all') {
            return Excel::download(new ViolationExport($this->exportStartDate, $this->exportEndDate, $this->dorm_id), 'rekap_pelanggaran.xlsx');
        } else {
            session()->flash('error', 'Tipe download tidak valid.');
        }
    }
}
