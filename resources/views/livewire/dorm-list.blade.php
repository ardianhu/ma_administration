<section class="w-full">
    @include('partials.dorms-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg flex-1 font-semibold text-gray-900 dark:text-white">Daftar User</h2>
                    <div class="flex items-center justify-end flex-1 gap-2">
                        <flux:input icon="magnifying-glass" class="hidden md:inline" wire:model.live.debounce.300ms="search" placeholder="Cari asrama" />
                        <a href={{ route('dorms.form') }}>
                            <flux:button variant="primary">Tambah Asrama</flux:button>
                        </a>
                    </div>
                </div>
                <div class="mb-4">
                    <flux:input icon="magnifying-glass" class="inline md:hidden" wire:model.live.debounce.300ms="search" placeholder="Cari asrama" />
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                        <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Blok - Kamar
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Kapasitas
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Kawasan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dorms as $dorm)
                            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200">
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $dorm->block }} - {{ $dorm->room_number }}   
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $dorm->capacity }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $dorm->zone }}
                                </th>
                                <td class="px-6 py-4">
                                    {{-- <a href="{{ route('users.edit', $user->id) }}" class="bg-zinc-500 hover:bg-zinc-700 text-white font-bold py-2 px-4 rounded">
                                        Edit
                                    </a> --}}
                                    <a href="{{ route('dorms.edit', $dorm->id) }}">     
                                        <flux:button variant="primary" icon="pencil" />
                                    </a>
                                    <a href="{{ route('dorms.member', $dorm->id) }}">     
                                        <flux:button variant="filled" icon="eye" />
                                    </a>
                                    <flux:modal.trigger name="delete-dorm">
                                        <flux:button variant="danger" icon="trash" wire:click="deleteSelected({{ $dorm->id }})"/>
                                    </flux:modal.trigger>
                                    {{-- <flux:button variant="primary" wire:click="deleteUser({{ $user->id }})" variant="danger">Hapus</flux:button> --}}
                
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                <div class="mt-4">{{ $dorms->links() }}</div>
            </div>
            {{-- content end here --}}
        </div>
    </div>
    <flux:modal name="delete-dorm" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus asrama?</flux:heading>
                <flux:text class="mt-2">
                    <p>Data asrama ini akan dihapus dari sistem.</p>
                    <p>Dan santri di asrama ini akan dikeluarkan dari asrama.</p>
                    <p>Aksi ini berdamapak permanen dan tidak bisa dikembalikan.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="deleteDorm()">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
</section>
