<?php

namespace App\Livewire;

use App\Models\Role;
use Livewire\Component;

class RoleForm extends Component
{
    public $role;
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function mount($role = null)
    {
        if ($role) {
            $this->role = Role::findOrFail($role);;
            $this->name = $this->role->name;
        }
    }

    public function save()
    {
        $this->validate();
        $isNew = !$this->role;

        if ($isNew) {
            $this->role = new Role();
        }

        $this->role->name = $this->name;
        $this->role->save();
        session()->flash('message', $isNew ? 'Role created successfully.' : 'Role updated successfully.');
        return redirect()->route('roles');
    }
    public function render()
    {
        return view('livewire.role-form');
    }
}
