<?php

namespace App\Livewire;

use App\Models\IslamicClass;
use Livewire\Component;

class IslamicClassForm extends Component
{
    public $islamicClass;
    public $name;
    public $class;
    public $sub_class;

    protected $rules = [
        'name' => 'required|string|max:255',
        'class' => 'required|string|max:255',
        'sub_class' => 'required|string|max:255',
    ];

    public function mount($islamicClass = null)
    {
        if ($islamicClass) {
            $this->islamicClass = IslamicClass::findOrFail($islamicClass);
            $this->name = $this->islamicClass->name;
            $this->class = $this->islamicClass->class;
            $this->sub_class = $this->islamicClass->sub_class;
        }
    }

    public function save()
    {
        $this->validate();
        $isNew = !$this->islamicClass;

        if ($isNew) {
            $this->islamicClass = new IslamicClass();
        }

        $this->islamicClass->name = $this->name;
        $this->islamicClass->class = $this->class;
        $this->islamicClass->sub_class = $this->sub_class;
        $this->islamicClass->save();

        session()->flash('message', $isNew ? 'Kelas berhasil dibuat.' : 'Kelas berhasil diupdate.');
        return redirect()->route('class');
    }

    public function render()
    {
        return view('livewire.islamic-class-form');
    }
}
