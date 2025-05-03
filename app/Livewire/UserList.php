<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
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
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(10);
        return view('livewire.user-list', [
            'users' => $users,
        ]);
    }

    public function deleteSelected($itemSelected)
    {
        $this->selected = $itemSelected;
    }

    public function deleteUser()
    {
        $user = User::find($this->selected);
        if ($user) {
            $user->delete();
            $this->selected = 0;
            session()->flash('message', 'User berhasil dihapus.');
            return redirect()->route('users');
        } else {
            session()->flash('error', 'User tidak ditemukan.');
        }
    }
}
