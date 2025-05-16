<?php

namespace App\Livewire;

use App\Models\Dorm;
use Livewire\Component;

class DormForm extends Component
{
    public $dorm;
    public $block;
    public $room_number;
    public $capacity;
    public $zone = '';

    protected $rules = [
        'block' => 'required|string|max:2',
        'room_number' => 'required|integer|min:1',
        'capacity' => 'required|integer|min:1',
        'zone' => 'required|in:putra,putri',
    ];

    public function mount($dorm = null)
    {
        if ($dorm) {
            $this->dorm = Dorm::findOrFail($dorm);
            $this->block = $this->dorm->block;
            $this->room_number = $this->dorm->room_number;
            $this->capacity = $this->dorm->capacity;
            $this->zone = $this->dorm->zone;
        }
    }

    public function save()
    {
        $this->validate();
        $isNew = !$this->dorm;

        if ($isNew) {
            $this->dorm = new Dorm();
        }

        $this->dorm->block = $this->block;
        $this->dorm->room_number = $this->room_number;
        $this->dorm->capacity = $this->capacity;
        $this->dorm->zone = $this->zone;
        $this->dorm->save();

        session()->flash('message', $isNew ? 'Asrama berhasil dibuat.' : 'Asrama berhasil diupdate.');
        return redirect()->route('dorms');
    }
    public function render()
    {
        return view('livewire.dorm-form');
    }
}
