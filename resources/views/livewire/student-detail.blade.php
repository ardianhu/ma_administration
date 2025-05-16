<section class="w-full">
    @include('partials.students-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div class="p-4 lg:p-8 h-full border border-solid border-zinc-200 dark:border-zinc-700 rounded-lg space-y-4">
                <div class="font-bold text-xl">Detail santri</div>
                <div class="space-y-2">
                    <div>Nama : <span>{{ $student->name }}</span></div>
                    <div>Alamat : <span>{{ ($student->address) ? $student->address : '-' }}</span></div>
                    <div>Ttl. : <span>{{ ($student->dob) ? $student->dob : '-' }}</span></div>
                    <div>Jenis Kelamin : <span>{{ $student->gender }}</span></div>
                    <div>Kamar: <span>{{ ($student->dorm ? $student->dorm->block .' - '. $student->dorm->room_number : '-') }}</span></div>
                    <div>Kelas: <span>{!! ($student->islamicClass) ? $student->islamicClass->name .' - '. $student->islamicClass->class .'<sup>'. $student->islamicClass->sub_class .'</sup>' : '-' !!}</span></div>
                    <div>Nama Ayah : <span>{{ ($student->father_name) ? $student->father_name : '-' }}</span></div>
                    <div>Nama Ibu: <span>{{ ($student->mother_name) ? $student->mother_name : '-' }}</span></div>
                    <div>Nama Wali: <span>{{ ($student->guardian_name) ? $student->guardian_name : '-' }}</span></div>
                </div>
            </div>
            <div class="p-4 lg:p-8 h-full border border-solid border-zinc-200 dark:border-zinc-700 rounded-lg space-y-4 mt-4">
                <div class="font-bold text-xl">Riwayat Izin</div>
                @if ($student)
                <div class="grid grid-flow-col grid-rows-7 gap-1 text-xs overflow-x-auto">
                    @foreach($heatmap as $date => $status)
                        @php
                            $color = match($status) {
                                'green' => 'bg-green-500',
                                'yellow' => 'bg-yellow-400',
                                'red' => 'bg-red-500',
                                default => 'bg-zinc-500',
                            };
                        @endphp
                        <div class="{{ $color }} w-2 h-2 lg:w-4 lg:h-4 rounded-xs" title="{{ $date }}"></div>
                    @endforeach
                </div>               
                @endif
                <div class="flex items-center justify-start gap-2">
                    <div>
                        <div class="bg-green-500 w-2 h-2 rounded-xs inline-block"></div>
                        <div class="inline-block text-xs">Ada di pondok</div>
                    </div>
                    <div>
                        <div class="bg-yellow-400 w-2 h-2 rounded-xs inline-block"></div>
                        <div class="inline-block text-xs">Izin pulang</div>
                    </div>
                    <div>
                        <div class="bg-red-500 w-2 h-2 rounded-xs inline-block"></div>
                        <div class="inline-block text-xs">Telat</div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <flux:button icon="plus" variant="primary" onclick="window.location.href='{{ route('permits.form', ['student' => $student->id]) }}'">Buat Izin</flux:button>
                </div>
            </div>
            <div class="flex justify-end items-center gap-4 mt-4">
                <flux:button icon="pencil" variant="primary" onclick="window.location.href='{{ route('students.edit', ['student' => $student->id]) }}'">Edit</flux:button>
                <flux:modal.trigger name="drop_student">
                    <flux:button icon="no-symbol" variant="filled">Berhenti</flux:button>
                </flux:modal.trigger>
                <flux:modal.trigger name="delete_student">
                    <flux:button icon="trash" variant="danger">Hapus</flux:button>
                </flux:modal.trigger>
            </div>
            {{-- content end here --}}
        </div>
    </div>
    <flux:modal name="delete_student" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus santri?</flux:heading>
                <flux:text class="mt-2">
                    <p>Data santri akan dihapus dari sistem.</p>
                    <p>Aksi ini berdamapak permanen dan tidak bisa dikembalikan.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="deleteStudent()">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
    <flux:modal name="drop_student" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Berhenti</flux:heading>
                <flux:text class="mt-2">
                    <p>Ubah status santri menjadi berhenti?</p>
                </flux:text>
            </div>
            <flux:input wire:model="drop_date" label="Tgl. Berhenti" type="date" />
            <flux:input wire:model="drop_reason" label="Alasan" type="text" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="dropStudent()">Berhenti</flux:button>
            </div>
        </div>
    </flux:modal>
</section>