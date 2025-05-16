<?php

namespace App\Livewire;

use App\Models\Permit;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class PermitList extends Component
{
    use WithPagination;

    public $search = '';
    public $selected = 0;
    public $extended_back_on;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Permit::whereHas('academicYear', function ($query) {
            $query->where('is_active', true);
        })
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });

        if (request()->has('status')) {
            $status = request()->get('status');
            if ($status == 'active') {
                $query->whereNull('arrive_on');
            } elseif ($status == 'inactive') {
                $query->whereNotNull('arrive_on');
            } elseif ($status == 'late') {
                $query->where('back_on', '<', Carbon::today());
            }
        }

        $permits = $query->orderBy('back_on', 'desc')->paginate(10);

        return view('livewire.permit-list', [
            'permits' => $permits,
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
}
