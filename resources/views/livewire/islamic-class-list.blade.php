<section class="w-full">
    @include('partials.class-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg flex-1 font-semibold text-gray-900 dark:text-white">Daftar Kelas</h2>
                    <div class="flex items-center justify-end flex-1 gap-2">
                        <flux:input icon="magnifying-glass" class="hidden md:inline" wire:model.live.debounce.300ms="search" placeholder="Cari kelas" />
                        <a href={{ route('class.form') }}>
                            <flux:button variant="primary">Tambah Kelas</flux:button>
                        </a>
                    </div>
                </div>
                <div class="mb-4">
                    <flux:input icon="magnifying-glass" class="inline md:hidden" wire:model.live.debounce.300ms="search" placeholder="Cari kelas" />
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                        <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Kelas
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($islamic_classes as $islamic_class)
                            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200">
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $islamic_class->name }} - {{ $islamic_class->class }}<sup class="">{{ $islamic_class->sub_class }}</sup>
                                </th>
                                <td class="px-6 py-4">
                                    <a href="{{ route('class.edit', $islamic_class->id) }}">
                                        <flux:button icon="pencil" variant="primary" />
                                    </a>
                                    <a href="{{ route('class.member', $islamic_class->id) }}">
                                        <flux:button icon="eye" variant="filled" />
                                    </a>
                                    <flux:modal.trigger name="delete-profile">
                                        <flux:button icon="trash" variant="danger" wire:click="deleteSelected({{ $islamic_class->id }})" />
                                    </flux:modal.trigger>
                
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                <div class="mt-4">{{ $islamic_classes->links() }}</div>
            </div>
            {{-- content end here --}}
        </div>
    </div>
    <flux:modal name="delete-profile" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus kelas?</flux:heading>
                <flux:text class="mt-2">
                    <p>Data kelas ini akan dihapus dari sistem.</p>
                    <p>Aksi ini berdamapak permanen dan tidak bisa dikembalikan.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="deleteislamic_class()">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
</section>