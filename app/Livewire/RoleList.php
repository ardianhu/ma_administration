<?php

namespace App\Livewire;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class RoleList extends Component
{
    use WithPagination;

    public function render()
    {
        $roles = Role::orderBy('name', 'asc')->paginate(10);
        return view('livewire.role-list', [
            'roles' => $roles,
        ]);
    }
    public function deleteRole($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            session()->flash('message', 'Role berhasil dihapus.');
            return redirect()->route('roles');
        } else {
            session()->flash('error', 'Role tidak ditemukan.');
        }
    }
}
