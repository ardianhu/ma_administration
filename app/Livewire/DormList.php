<?php

namespace App\Livewire;

use App\Models\Dorm;
use Livewire\Component;
use Livewire\WithPagination;

class DormList extends Component
{
    use WithPagination;
    public $search = '';
    public $selected = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $dorms = Dorm::where('block', 'like', '%' . $this->search . '%')
            ->orWhere('room_number', 'like', '%' . $this->search . '%')
            ->orderBy('block', 'asc')
            ->paginate(10);
        return view('livewire.dorm-list', [
            'dorms' => $dorms,
        ]);
    }

    public function deleteSelected($itemSelected)
    {
        $this->selected = $itemSelected;
    }

    public function deleteDorm()
    {
        $dorm = Dorm::find($this->selected);
        if ($dorm) {
            $students = $dorm->students()->get();
            foreach ($students as $student) {
                $student->dorm_id = null;
                $student->save();
            }
            $dorm->delete();
            $this->selected = 0;
            session()->flash('message', 'Asrama berhasil dihapus.');
            return redirect()->route('dorms');
        } else {
            session()->flash('error', 'Asrama tidak ditemukan.');
        }
    }
}
