<?php

namespace App\Livewire;

use App\Exports\PermitExport;
use App\Exports\SimplePermitExport;
use App\Models\Dorm;
use App\Models\Permit;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class PermitList extends Component
{
    use WithPagination;

    public $search = '';
    public $selected = 0;
    public $extended_back_on;

    public $exportStartDate;
    public $exportEndDate;

    public $dorm_id = '';
    public $download_type = '';

    public $statusFilter = '';

    public function mount()
    {
        if (request()->has('status')) {
            $this->statusFilter = request()->get('status');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Permit::whereHas('academicYear', function ($query) {
            $query->where('is_active', true);
        })
            ->whereHas('student', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });

        if ($this->statusFilter) {
            if ($this->statusFilter == 'active') {
                $query->whereNull('arrive_on');
            } elseif ($this->statusFilter == 'inactive') {
                $query->whereNotNull('arrive_on');
            } elseif ($this->statusFilter == 'late') {
                // $query->where('back_on', '<', Carbon::today());
                // back_on sebelum hari ini
                $query->where('back_on', '<', Carbon::now()->startOfDay())->where('arrive_on', null);
            } elseif ($this->statusFilter == 'late_arrived') {
                // back_on sebelum arrive_on
                $query->whereNotNull('arrive_on')
                    ->whereColumn('back_on', '<', 'arrive_on');
            }
        }

        $permits = $query->orderBy('back_on', 'desc')->paginate(10);

        $dorm = Dorm::all();

        return view('livewire.permit-list', [
            'permits' => $permits,
            'dorms' => $dorm,
        ]);
    }

    public function deleteSelected($itemSelected)
    {
        $this->selected = $itemSelected;
    }

    public function permitSelected($itemSelected)
    {
        $this->selected = $itemSelected;
    }

    public function deletePermit()
    {
        $permit = Permit::find($this->selected);
        if ($permit) {
            $permit->delete();
            $this->selected = 0;
            session()->flash('message', 'Izin berhasil dihapus.');
            return redirect()->route('permits');
        } else {
            session()->flash('error', 'Izin tidak ditemukan.');
        }
    }

    public function arrivalConfirm()
    {
        $permit = Permit::find($this->selected);
        if ($permit) {
            $permit->arrive_on = Carbon::now();
            $permit->save();
            $this->selected = 0;
            session()->flash('message', 'Kedatangan berhasil dikonfirmasi.');
            return redirect()->route('permits');
        } else {
            session()->flash('error', 'Izin tidak ditemukan.');
        }
    }

    public function extendPermit()
    {
        $permit = Permit::find($this->selected);
        if ($permit) {
            $permit->extended_count += 1;
            $permit->back_on = $this->extended_back_on;
            $permit->save();
            $this->selected = 0;
            session()->flash('message', 'Izin berhasil diperpanjang.');
            return redirect()->route('permits');
        } else {
            session()->flash('error', 'Izin tidak ditemukan.');
        }
    }

    public function downloadPermit()
    {
        if ($this->download_type == 'accumulation') {
            return Excel::download(new SimplePermitExport($this->exportStartDate, $this->exportEndDate, $this->dorm_id), 'akumulasi_izin.xlsx');
        } elseif ($this->download_type == 'all') {
            return Excel::download(new PermitExport($this->exportStartDate, $this->exportEndDate, $this->dorm_id), 'rekap_izin.xlsx');
        } else {
            session()->flash('error', 'Tipe download tidak valid.');
        }
    }
}
