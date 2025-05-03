<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class UserForm extends Component
{
    public $user;
    public $name;
    public $email;
    public $password;
    public $role_id = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'role_id' => 'required|exists:roles,id',
    ];

    public function mount($user = null)
    {
        if ($user) {
            $this->user = User::findOrFail($user);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->role_id = $this->user->role_id;
        }
    }

    public function save()
    {
        $this->validate();
        $isNew = !$this->user;

        if ($isNew) {
            $this->user = new User();
        }

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        if ($this->password) {
            $this->user->password = bcrypt($this->password);
        }
        $this->user->role_id = $this->role_id;
        $this->user->save();
        session()->flash('message', $isNew ? 'User berhasil dibuat.' : 'User berhasil diupdate.');
        return redirect()->route('users');
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.user-form', [
            'roles' => $roles,
        ]);
    }
}
