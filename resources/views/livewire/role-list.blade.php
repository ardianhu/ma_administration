<section class="w-full">
    @include('partials.roles-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Role</h2>
                    <a href={{ route('roles.form') }}>
                        <flux:button variant="primary">Tambah Role</flux:button>
                    </a>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                        <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nama Role
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200">
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $role->name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{-- <a href="{{ route('roles.edit', $role->id) }}" class="bg-zinc-500 hover:bg-zinc-700 text-white font-bold py-2 px-4 rounded">
                                        Edit
                                    </a> --}}
                                    <a href="{{ route('roles.edit', $role->id) }}">     
                                        <flux:button variant="primary">Edit</flux:button>
                                    </a>
                                    <flux:button variant="primary" wire:click="deleteRole({{ $role->id }})" variant="danger">Hapus</flux:button>
                
                                    {{-- <form action="{{ route('roles.delete', ['id' => $role->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2">
                                            Hapus
                                        </button>
                                    </form> --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                
                  
                    <div class="mt-4">{{ $roles->links() }}</div>
                </div>
            {{-- content end here --}}
        </div>
    </div>
</section>
