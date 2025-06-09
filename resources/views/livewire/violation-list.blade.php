<section class="w-full">
    @include('partials.violations-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg flex-1 font-semibold text-gray-900 dark:text-white">Daftar Pelanggaran</h2>
                    <div class="flex items-center justify-end flex-1 gap-2">
                        <flux:dropdown>
                            <flux:button icon="funnel" />

                            <flux:menu>
                                <flux:menu.item href="{{ route('violations') }}">Semua</flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item href="{{ route('violations', ['type' => 'leave']) }}">Pulang</flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item href="{{ route('violations', ['type' => 'leave_not_returned']) }}">Belum kembali</flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item href="{{ route('violations', ['type' => 'others']) }}">Lainnya</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                        <flux:modal.trigger name="download-violation">
                            <flux:button icon="folder-arrow-down" />
                        </flux:modal.trigger>
                        <flux:input icon="magnifying-glass" class="hidden md:inline" wire:model.live.debounce.300ms="search" placeholder="Cari santri" autocomplete="off" />
                        @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'keamanan')
                        <a href={{ route('violations.form') }}>
                            <flux:button icon="plus" variant="primary">Pelanggaran</flux:button>
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
                                    Jenis Pelanggaran
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tgl. Pelanggaran
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tgl. Kembali
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
                            @foreach($violations as $violation)
                            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200">
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $violation->student->name }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $violation->violation_type }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $violation->violation_date->format('d-F-Y') }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ ($violation->resolved_at) ? $violation->resolved_at->format('d-F-Y') : '-' }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    @if ($violation)
                                        @if ($violation->violation_type == 'pulang')
                                            @if ($violation->resolved_at == null && (intval($violation->violation_date->diffInDays(now()))) > 16)
                                                <flux:badge color="red">Pulang 15+ hari</flux:badge>
                                            @elseif ($violation->resolved_at == null)
                                                <flux:badge color="orange">Pulang</flux:badge>
                                            @else
                                                <flux:badge color="green">Kembali</flux:badge>
                                            @endif
                                        @endif
                                    @endif
                                </th>
                                <td class="px-6 py-4">
                                    {{-- <a href="{{ route('users.edit', $user->id) }}" class="bg-zinc-500 hover:bg-zinc-700 text-white font-bold py-2 px-4 rounded">
                                        Edit
                                    </a> --}}
                                    {{-- <a href="{{ route('violations.edit', $violation->id) }}">     
                                        <flux:button variant="primary">Edit</flux:button>z
                                    </a> --}}
                                    {{-- <flux:modal.trigger name="delete-profile">
                                        <flux:button variant="danger" wire:click="deleteSelected({{ $violation->id }})">Hapus</flux:button>
                                    </flux:modal.trigger> --}}
                                    @if ($violation->resolved_at == null && $violation->violation_type == 'pulang')
                                    @if (
                                        (auth()->user()->role->name == 'admin') ||
                                        (auth()->user()->role->name == 'keamanan' && $violation->violation_type == 'kepentingan')
                                    )
                                    <flux:modal.trigger name="confirm_arrival">
                                        <flux:button icon="check" variant="primary" wire:click="violationSelected({{ $violation->id }})" />
                                    </flux:modal.trigger>
                                    <flux:modal.trigger name="delete_violation">
                                        <flux:button icon="trash" variant="danger" wire:click="violationSelected({{ $violation->id }})" />
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
                    @foreach ($violations as $violation)
                    <flux:modal.trigger name="{{ ($violation->resolved_at == null && $violation->violation_type == 'pulang') ? 'confirm_arrival' : '' }}">
                    <a href="#" wire:click="violationSelected({{ $violation->id }})">
                    <div class="p-4 lg:p-8 h-full border border-solid border-zinc-200 dark:border-zinc-700 rounded-xl space-y-4 mb-2">
                        <div class="flex justify-center items-center">
                            <div class="grow-2">
                                <div class="font-bold text-xl">{{ $violation->student->name }}</div>
                                <div class="">{{ $violation->violation_type }}</div>
                                <div class="text-sm">{{ $violation->violation_date->format('d-F') }}{{ $violation->resolved_at ? '-> ' . $violation->resolved_at->format('d-F') : '' }}</div>
                            </div>
                            <div class="grow-1">
                                <div class="flex justify-end">
                                    @if ($violation)
                                        @if ($violation->violation_type == 'pulang')
                                            @if ($violation->resolved_at == null && (intval($violation->violation_date->diffInDays(now()))) > 16)
                                                <flux:badge color="red">Pulang 15+ hari</flux:badge>
                                            @elseif ($violation->resolved_at == null)
                                                <flux:badge color="orange">Pulang</flux:badge>
                                            @else
                                                <flux:badge color="green">Kembali</flux:badge>
                                            @endif
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
                <div class="mt-4">{{ $violations->links() }}</div>
            </div>
            {{-- content end here --}}
        </div>
    </div>
    <flux:modal name="delete-violation" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus pelanggaran?</flux:heading>
                <flux:text class="mt-2">
                    <p>Data pelanggaran ini akan dihapus dari sistem.</p>
                    <p>Aksi ini berdamapak permanen dan tidak bisa dikembalikan.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="deleteViolation()">Hapus</flux:button>
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

    <flux:modal name="download-violation" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Download rekap pelanggaran</flux:heading>
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
            <flux:input wire:model="exportStartDate" label="Mulai tanggal" type="date" />
            <flux:input wire:model="exportEndDate" label="Sampai tanggal" type="date" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="downloadViolation()">Download</flux:button>
            </div>
        </div>
    </flux:modal>
</section>