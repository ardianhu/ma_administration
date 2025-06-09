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
                @php
                    $previousMonth = null;
                    $counter = 0;
                @endphp
                {{-- github like heatmap container --}}
                <div class="overflow-x-auto">
                {{-- Month labels --}}
                <div class="grid grid-flow-col grid-rows-1 gap-1 text-xs">
                    @foreach($heatmap->chunk(7) as $week)
                        @php
                            $weekStartDate = \Carbon\Carbon::parse($week->keys()->first());
                            $currentMonth = $weekStartDate->format('M');
                        @endphp
                        <div class="w-2 lg:w-4 text-center">
                            @if ($currentMonth !== $previousMonth)
                                {{ $currentMonth }}
                                @php $previousMonth = $currentMonth; @endphp
                            @endif
                        </div>
                    @endforeach
                </div>
                {{-- Heatmap --}}
                @php
                    $dates = $heatmap->keys()->values();
                    $values = $heatmap->values()->toArray();
                @endphp
                            
                <div class="grid grid-flow-col grid-rows-7 gap-1 text-xs">
                    @foreach($heatmap as $i => $status)
                        @php
                            $color = match($status) {
                                'green' => 'bg-green-500',
                                'yellow' => 'bg-yellow-400',
                                'red' => 'bg-red-400',
                                'darker_red' => 'bg-red-800',
                                default => 'bg-zinc-500',
                            };
                        
                            $prev = $values[$loop->index - 1] ?? null;
                            $next = $values[$loop->index + 1] ?? null;
                        
                            $isStart = $status !== $prev;
                            $isEnd = $status !== $next;
                        
                            $date = $dates[$loop->index];
                            $day = \Carbon\Carbon::parse($date)->format('d');
                            $showDay = $isStart || $isEnd;
                        @endphp
                
                        <div class="{{ $color }} w-2 h-2 lg:w-4 lg:h-4 text-[6px] lg:text-xs text-center rounded-[1px] lg:rounded-xs" title="{{ $date }}">
                            {{ $showDay ? $day : '' }}
                        </div>
                    @endforeach
                </div>
                </div>
                {{-- End of heatmap container --}}            
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
                    <div>
                        <div class="bg-red-800 w-2 h-2 rounded-xs inline-block"></div>
                        <div class="inline-block text-xs">Tanpa izin</div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'keamanan' || auth()->user()->role->name == 'kesehatan')
                        <flux:button icon="plus" variant="primary" onclick="window.location.href='{{ route('permits.form', ['student' => $student->id]) }}'">Izin</flux:button>
                    @endif
                    @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'keamanan')
                        <flux:button icon="plus" variant="primary" onclick="window.location.href='{{ route('violations.form', ['student' => $student->id]) }}'">Pelanggaran</flux:button>
                    @endif
                </div>
            </div>
            @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'sekretaris')
            <div class="flex justify-end items-center gap-4 mt-4">
                <flux:button icon="pencil" variant="primary" onclick="window.location.href='{{ route('students.edit', ['student' => $student->id]) }}'">Edit</flux:button>
                <flux:modal.trigger name="drop_student">
                    <flux:button icon="no-symbol" variant="filled">Berhenti</flux:button>
                </flux:modal.trigger>
                <flux:modal.trigger name="delete_student">
                    <flux:button icon="trash" variant="danger">Hapus</flux:button>
                </flux:modal.trigger>
            </div>
            @endif
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