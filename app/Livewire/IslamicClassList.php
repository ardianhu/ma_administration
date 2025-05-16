<?php

namespace App\Livewire;

use App\Models\IslamicClass;
use Livewire\Component;
use Livewire\WithPagination;

class IslamicClassList extends Component
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
        $islamic_classes = IslamicClass::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('class', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(10);
        return view('livewire.islamic-class-list', [
            'islamic_classes' => $islamic_classes,
        ]);
    }

    public function deleteSelected($itemSelected)
    {
        $this->selected = $itemSelected;
    }

    public function deleteIslamicClass()
    {
        $islamic_class = IslamicClass::find($this->selected);
        if ($islamic_class) {
            $islamic_class->delete();
            $this->selected = 0;
            session()->flash('message', 'Kelas berhasil dihapus.');
            return redirect()->route('class');
        } else {
            session()->flash('error', 'Kelas tidak ditemukan.');
        }
    }
}
