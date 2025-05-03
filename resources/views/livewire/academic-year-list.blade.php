<section class="w-full">
    @include('partials.academic-years-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg flex-1 font-semibold text-gray-900 dark:text-white">Tahun Ajar</h2>
                    <div class="flex items-center justify-end flex-1 gap-2">
                        <a href={{ route('academic-years.form') }}>
                            <flux:button variant="primary">Tambah Tahun Ajar</flux:button>
                        </a>
                    </div>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                        <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Tahun Ajar
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($academicYears as $academicYear)
                            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200">
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ \Carbon\Carbon::parse($academicYear->start)->format('Y') }} - {{ \Carbon\Carbon::parse($academicYear->end)->format('Y') }}
                                    @if ($academicYear->is_active)
                                        (Aktif)
                                        
                                    @endif
                                </th>
                                <td class="px-6 py-4">
                                    <a href="{{ route('academic-years.edit', $academicYear->id) }}">     
                                        <flux:button variant="primary">Edit</flux:button>
                                    </a>
                                    <flux:modal.trigger name="delete-profile">
                                        <flux:button variant="danger" wire:click="deleteSelected({{ $academicYear->id }})">Hapus</flux:button>
                                    </flux:modal.trigger>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $academicYears->links() }}</div>
            </div>
            {{-- content end here --}}
        </div>
    </div>
    <flux:modal name="delete-profile" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus tahun ajar?</flux:heading>
                <flux:text class="mt-2">
                    <p>Tahun ajar akan dihapus dari sistem.</p>
                    <p>Aksi ini berdamapak permanen dan tidak bisa dikembalikan.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="deleteAcademicYear()">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
</section>