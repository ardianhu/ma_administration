<section class="w-full">
    @include('partials.students-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg flex-1 font-semibold text-gray-900 dark:text-white">Data Santri</h2>
                    <div class="flex items-center justify-end flex-1 gap-2">
                        <flux:input icon="magnifying-glass" class="hidden md:inline" wire:model.live.debounce.300ms="search" placeholder="Cari santri" />
                        <a href={{ route('students.form') }}>
                            <flux:button variant="primary">Tambah Santri</flux:button>
                        </a>
                    </div>
                </div>
                <div class="mb-4">
                    <flux:input icon="magnifying-glass" class="inline md:hidden" wire:model.live.debounce.300ms="search" placeholder="Cari santri" />
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                        <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Alamat
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tempat Tanggal Lahir
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200">
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $student->name }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $student->address }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $student->dob }}
                                </th>
                                <td class="px-6 py-4">
                                    <a href="{{ route('students.edit', $student->id) }}">     
                                        <flux:button variant="primary">Edit</flux:button>
                                    </a>
                
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                <div class="mt-4">{{ $students->links() }}</div>
            </div>
            {{-- content end here --}}
        </div>
    </div>
</section>