<section class="w-full">
    @include('partials.permits-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg flex-1 font-semibold text-gray-900 dark:text-white">Daftar Perizinan</h2>
                    <div class="flex items-center justify-end flex-1 gap-2">
                        <flux:dropdown>
                            <flux:button icon="funnel" />

                            <flux:menu>
                                <flux:menu.item href="{{ route('permits') }}">Semua</flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item href="{{ route('permits', ['status' => 'active']) }}">Belum datang</flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item href="{{ route('permits', ['status' => 'inactive']) }}">Datang</flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item href="{{ route('permits', ['status' => 'late']) }}">Lambat</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                        <flux:modal.trigger name="download-permit">
                            <flux:button icon="folder-arrow-down" />
                        </flux:modal.trigger>
                        <flux:input icon="magnifying-glass" class="hidden md:inline" wire:model.live.debounce.300ms="search" placeholder="Cari santri" autocomplete="off" />
                        @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'keamanan' || auth()->user()->role->name == 'kesehatan')
                        <a href={{ route('permits.form') }}>
                            <flux:button icon="plus" variant="primary">Izin</flux:button>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <flux:input icon="magnifying-glass" class="inline md:hidden" wire:model.live.debounce.300ms="search" placeholder="Cari santri" autocomplete="off" />
                </div>
                <div class="relative overflow-x-auto hidden lg:block">
                    <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                        <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Jenis Izin
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tgl. Izin
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tgl. Kembali
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tgl. Tiba
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Alasan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Diperpanjang
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permits as $permit)
                            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200">
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $permit->student->name }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $permit->permit_type }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $permit->leave_on->format('d-F-Y') }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ ($permit->back_on) ? $permit->back_on->format('d-F-Y') : '-' }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ ($permit->arrive_on) ? \Carbon\Carbon::parse($permit->arrive_on)->format('d-F-Y') : '-' }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $permit->reason }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ ($permit->extended_count == 0) ? 'Tidak pernah' : $permit->extended_count .' kali' }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    @if ($permit->arrive_on)
                                        @if ($permit->arrive_on <= $permit->back_on)
                                            <flux:badge color="green">Datang tepat waktu</flux:badge>
                                        @else
                                            <flux:badge color="orange">
                                                Datang telat ({{ (intval($permit->back_on->diffInDays($permit->arrive_on))) + 1 }} hari)
                                            </flux:badge>
                                        @endif
                                    @else
                                        @if ($permit->back_on <= now())
                                            <flux:badge color="red">
                                                Belum datang (telat {{ (intval($permit->back_on->diffInDays(now()))) + 1 }} hari)
                                            </flux:badge>
                                        @else
                                            <flux:badge color="zinc">Belum datang</flux:badge>
                                        @endif
                                    @endif
                                </th>
                                <td class="px-6 py-4">
                                    {{-- <a href="{{ route('users.edit', $user->id) }}" class="bg-zinc-500 hover:bg-zinc-700 text-white font-bold py-2 px-4 rounded">
                                        Edit
                                    </a> --}}
                                    {{-- <a href="{{ route('permits.edit', $permit->id) }}">     
                                        <flux:button variant="primary">Edit</flux:button>z
                                    </a> --}}
                                    {{-- <flux:modal.trigger name="delete-profile">
                                        <flux:button variant="danger" wire:click="deleteSelected({{ $permit->id }})">Hapus</flux:button>
                                    </flux:modal.trigger> --}}
                                    @if (!$permit->arrive_on)
                                    @if (
                                        (auth()->user()->role->name == 'admin') ||
                                        (auth()->user()->role->name == 'keamanan' && $permit->permit_type == 'kepentingan') ||
                                        (auth()->user()->role->name == 'kesehatan' && $permit->permit_type == 'sakit')
                                    )
                                    <flux:modal.trigger name="confirm_arrival">
                                        <flux:button icon="check" variant="primary" wire:click="permitSelected({{ $permit->id }})" />
                                    </flux:modal.trigger>
                                    <flux:modal.trigger name="extend_permit">
                                        <flux:button icon="plus" variant="filled" wire:click="permitSelected({{ $permit->id }})" />
                                    </flux:modal.trigger>
                                    @endif
                                    @endif
                                    {{-- <flux:button variant="primary" wire:click="deleteUser({{ $user->id }})" variant="danger">Hapus</flux:button> --}}
                
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                <div class="space-y-6 lg:hidden">
                    @foreach ($permits as $permit)
                    <flux:modal.trigger name="{{ (!$permit->arrive_on) ? 'mobile_modal' : '' }}">
                    <a href="#" wire:click="permitSelected({{ $permit->id }})">
                    <div class="p-4 lg:p-8 h-full border border-solid border-zinc-200 dark:border-zinc-700 rounded-xl space-y-4 mb-2">
                        <div class="flex justify-center items-center">
                            <div class="grow-2">
                                <div class="font-bold text-xl">{{ $permit->student->name }}</div>
                                <div class="">{{ $permit->permit_type }} ({{ $permit->reason }})</div>
                                <div class="text-sm">{{ $permit->leave_on->format('d-F') }} -> {{ ($permit->back_on) ? $permit->back_on->format('d-F') : '-' }}</div>
                            </div>
                            <div class="grow-1">
                                @if ($permit->extended_count > 0)
                                <div class="flex justify-end mb-1">
                                    <flux:badge size="sm" color="blue">Diperpanjang {{ $permit->extended_count }} kali</flux:badge>
                                </div>
                                @endif
                                <div class="flex justify-end">
                                    @if ($permit->arrive_on)
                                        @if ($permit->arrive_on <= $permit->back_on)
                                            <flux:badge size="sm" color="green">Datang tepat waktu</flux:badge>
                                        @else
                                            <flux:badge size="sm" color="orange">
                                                Datang telat ({{ (intval($permit->back_on->diffInDays($permit->arrive_on)) + 1) }} hari)
                                            </flux:badge>
                                        @endif
                                    @else
                                        @if ($permit->back_on <= now())
                                            <flux:badge size="sm" color="red">
                                                Belum datang (telat {{ (intval($permit->back_on->diffInDays(now())) + 1) }} hari)
                                            </flux:badge>
                                        @else
                                            <flux:badge size="sm" color="zinc">Belum datang</flux:badge>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                    </flux:modal.trigger>
                    @endforeach
                </div>
                <div class="mt-4">{{ $permits->links() }}</div>
            </div>
            {{-- content end here --}}
        </div>
    </div>
    <flux:modal name="delete-profile" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus izin?</flux:heading>
                <flux:text class="mt-2">
                    <p>Data izin ini akan dihapus dari sistem.</p>
                    <p>Aksi ini berdamapak permanen dan tidak bisa dikembalikan.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="deletePermit()">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
    <flux:modal name="confirm_arrival" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Konfirmasi</flux:heading>
                <flux:text class="mt-2">
                    <p>Konfirmasi kedatangan santri.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="filled" wire:click="arrivalConfirm()">Iya</flux:button>
            </div>
        </div>
    </flux:modal>
    
    <flux:modal name="extend_permit" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Perpanjang</flux:heading>
                <flux:text class="mt-2">Perpanjang durasi izin.</flux:text>
            </div>
            <flux:input wire:model="extended_back_on" label="Tgl. Kembali" type="datetime-local" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="extendPermit()">Save changes</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="download-permit" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Download rekap izin</flux:heading>
                <flux:text class="mt-2">file akan didownload dalam format excel.</flux:text>
            </div>
            <flux:select wire:model="dorm_id" :label="__('Kamar')" placeholder="Pilih kamar">
                @foreach ($dorms as $dorm)
                <flux:select.option value="{{ $dorm->id }}">{{ $dorm->block }}-{{ $dorm->room_number }}({{ $dorm->zone }})</flux:select.option>
                @endforeach
            </flux:select>
            <flux:text class="text-xs" color="">Abaikan kamar untuk download semua data.</flux:text>
            <flux:select wire:model="download_type" :label="__('Tipe Download')" placeholder="Pilih tipe download" required>
                <flux:select.option value="accumulation">Akumulasi</flux:select.option>
                <flux:select.option value="all">Semua</flux:select.option>
            </flux:select>
            <flux:input wire:model="exportStartDate" label="Mulai tanggal" type="date" required/>
            <flux:input wire:model="exportEndDate" label="Sampai tanggal" type="date" required/>
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="downloadPermit()">Download</flux:button>
            </div>
        </div>
    </flux:modal>

    @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'keamanan' || auth()->user()->role->name == 'kesehatan')
    <flux:modal name="mobile_modal" class="w-96 md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Aksi</flux:heading>
                <flux:text class="mt-2">Pilih untuk perpanjang atau konfirmasi santri telah balik pondok.</flux:text>
            </div>
            <flux:modal.trigger name="extend_permit">
                <flux:button icon="plus" class="w-full mb-2" variant="filled">Perpanjang Izin</flux:button>
            </flux:modal.trigger>
            <flux:modal.trigger name="confirm_arrival">
                <flux:button icon="check" class="w-full" variant="primary">Konfirmasi kedatangan</flux:button>
            </flux:modal.trigger>
            {{-- <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="extendPermit()">Save changes</flux:button>
            </div> --}}
        </div>
    </flux:modal>
    @endif
</section>