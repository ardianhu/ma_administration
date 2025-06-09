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
                        @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'sekretaris')
                        <flux:dropdown>
                            <flux:button icon="document" />

                            <flux:menu>
                                <flux:menu.item href="#">
                                    <flux:modal.trigger name="upload_excel">
                                        <flux:button icon="arrow-up-on-square" class="w-full" variant="filled">Upload Excel</flux:button>
                                    </flux:modal.trigger>
                                </flux:menu.item>
                                
                                <flux:menu.separator />
                                
                                <flux:menu.item href="#">
                                    <flux:button icon="arrow-down-on-square" class="w-full" variant="filled" wire:click="downloadExcel()">Download Excel</flux:button>
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                        <a href={{ route('students.form') }}>
                            <flux:button icon="plus" variant="primary">Santri</flux:button>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <flux:input icon="magnifying-glass" class="inline md:hidden" wire:model.live.debounce.300ms="search" placeholder="Cari santri" />
                </div>
                <div class="relative overflow-x-auto hidden lg:block">
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
                                    Kamar
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Kelas
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
                                    {{ ($student->dorm) ? $student->dorm->block : '' }}-{{ ($student->dorm) ? $student->dorm->room_number : '' }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ ($student->islamicClass) ? $student->islamicClass->name : '' }}-{{ ($student->islamicClass) ? $student->islamicClass->class : '' }}<sup>{{ ($student->islamicClass) ? $student->islamicClass->sub_class : '' }}</sup>
                                </th>
                                <td class="px-6 py-4">
                                    <a href="{{ route('students.detail', $student->id) }}">     
                                        <flux:button icon="eye" variant="primary" />
                                    </a>
                
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                <div class="space-y-6 lg:hidden">
                    @foreach ($students as $student)
                    <a href="{{ route('students.detail', $student->id) }}">
                    <div class="p-4 lg:p-8 h-full border border-solid border-zinc-200 dark:border-zinc-700 rounded-xl space-y-4 mb-2">
                        <div class="flex justify-center items-center">
                            <div class="grow-2">
                                <div class="font-bold text-xl">{{ $student->name }}</div>
                                <div class="">{{ $student->address  }}</div>
                                <div class="text-sm">Kamar {{ ($student->dorm) ? $student->dorm->block .'-'. $student->dorm->room_number : '-' }} | Kelas {{ ($student->islamicClass) ? $student->islamicClass->name .'-'. $student->islamicClass->class : '-'  }}</div>
                            </div>
                            <div class="grow-1">
                                <div class="flex justify-end items-end">
                                    @if ($student->status == 'no_permit')
                                        <flux:badge size="sm" color="green">Di pondok</flux:badge>
                                    @endif
                                    @if ($student->status == 'have_permit')
                                        <flux:badge size="sm" color="orange">Izin</flux:badge>
                                    @endif
                                    @if ($student->status == 'late')
                                        <flux:badge size="sm" color="red">Telat</flux:badge>
                                    @endif
                                    @if ($student->status == 'leave_not_returned')
                                        <flux:badge size="sm" color="red">Kabur</flux:badge>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                    @endforeach
                </div>
                <div class="mt-4">{{ $students->links() }}</div>
            </div>
            {{-- content end here --}}
        </div>
    </div>
    <flux:modal name="upload_excel" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Upload data santri</flux:heading>
                <flux:text class="mt-2">Pilih file dalam format excel.</flux:text>
            </div>
            <flux:select wire:model="dorm_id" :label="__('Kamar')" placeholder="Pilih kamar">
                @foreach ($dorms as $dorm)
                <flux:select.option value="{{ $dorm->id }}">{{ $dorm->block }}-{{ $dorm->room_number }}({{ $dorm->zone }})</flux:select.option>
                @endforeach
            </flux:select>
            <flux:input wire:model="file" label="File" type="file" />
            <div class="flex">
                <flux:spacer />
                <flux:button 
                    type="submit" 
                    variant="primary" 
                    wire:click="uploadExcel()"
                    wire:loading.attr="disabled"
                >Upload</flux:button>
            </div>
        </div>
    </flux:modal>
</section>