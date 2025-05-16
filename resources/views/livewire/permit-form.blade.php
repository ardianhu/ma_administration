<section class="w-full">
    @include('partials.permits-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <div class="mb-4">
                    <flux:input icon="magnifying-glass" class="inline" wire:model.live.debounce.300ms="search" placeholder="Cari santri" autocomplete="off"/>
                </div>
                <div class="mb-4">
                    <flux:navlist class="">
                        @if ($search != '')
                        @foreach ($students as $student)
                            <flux:navlist.item href="#" icon="user" wire:click="updateSelectedStudent({{ $student->id }})">{{ $student->name }} {{ ($student->dorm) ? $student->dorm->block : '' }}-{{ ($student->dorm) ? $student->dorm->room_number : '' }}</flux:navlist.item>
                        @endforeach
                        @endif
                        @if ($students->count() == 0 && $search != '')
                            <flux:navlist.item icon="exclamation-triangle">Santri tidak ditemukan</flux:navlist.item>
                        @endif
                    </flux:navlist>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 lg:gap-6">
                    <div class="mb-4 lg:mb-0">
                        @if ($selectedStudent)
                            <div class="p-4 lg:p-8 h-full border border-solid border-zinc-200 dark:border-zinc-700 rounded-lg space-y-4">
                                <div class="font-bold text-xl">Detail santri</div>
                                <div class="space-y-2">
                                    <div>Nama : <span>{{ $selectedStudent->name }}</span></div>
                                    <div>Alamat : <span>{{ ($selectedStudent->address) ? $selectedStudent->address : '-' }}</span></div>
                                    <div>Ttl. : <span>{{ ($selectedStudent->dob) ? $selectedStudent->dob : '-' }}</span></div>
                                    <div>Jenis Kelamin : <span>{{ $selectedStudent->gender }}</span></div>
                                    <div>Kamar: <span>{{ ($selectedStudent->dorm ? $selectedStudent->dorm->block .' - '. $selectedStudent->dorm->room_number : '-') }}</span></div>
                                    <div>Kelas: <span>{{ ($selectedStudent->islamicClass) ? $selectedStudent->islamicClass->name .' - '. $selectedStudent->islamicClass->class : '-' }}</span></div>
                                    <div>Nama Ayah : <span>{{ ($selectedStudent->father_name) ? $selectedStudent->father_name : '-' }}</span></div>
                                    <div>Nama Ibu: <span>{{ ($selectedStudent->mother_name) ? $selectedStudent->mother_name : '-' }}</span></div>
                                    <div>Nama Wali: <span>{{ ($selectedStudent->guardian_name) ? $selectedStudent->guardian_name : '-' }}</span></div>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-center items-center p-4 h-full border border-solid border-zinc-200 dark:border-zinc-700">
                                <div class="">Silahkan pilih santri terlebih dahulu</div>
                            </div>
                        @endif
                    </div>
                    <form wire:submit.prevent="save" class="mb-4 col-span-2">
                        <fieldset {{ $selectedStudent ? '' : 'disabled' }} class=" space-y-6">
                        <flux:input :label="__('Nama')" value="{{ ($selectedStudent) ? $selectedStudent->name : '' }}" type="text" disabled autocomplete="name" />
                        @error('student_id') <span class="text-red-500">{{ $message }}</span> @enderror
                        <flux:select wire:model="permit_type" :label="__('Jenis Izin')" placeholder="Sakit/Kepentingan">
                            <flux:select.option value="sakit">Sakit</flux:select.option>
                            <flux:select.option value="kepentingan">Kepentingan</flux:select.option>
                        </flux:select>
                        @error('gender') <span class="text-red-500">{{ $message }}</span> @enderror
                        <flux:input wire:model="leave_on" :label="__('Tgl. Pulang')" type="datetime-local" autofocus autocomplete="leave_on" />
                        @error('leave_on') <span class="text-red-500">{{ $message }}</span> @enderror
                        <flux:input wire:model="back_on" :label="__('Tgl. Kembali')" type="datetime-local" autofocus autocomplete="back_on" />
                        @error('back_on') <span class="text-red-500">{{ $message }}</span> @enderror
                        <flux:input wire:model="reason" :label="__('Kepentingan')" type="text" placeholder="Alasan izin" autofocus autocomplete="reason" />
                        @error('reason') <span class="text-red-500">{{ $message }}</span> @enderror
                        <flux:input wire:model="destination" :label="__('Tujuan')" type="text" placeholder="Tujuan Pulang" autofocus autocomplete="destination" />
                        @error('destination') <span class="text-red-500">{{ $message }}</span> @enderror
                      <div>
                        <flux:button type="submit" class="w-full lg:w-auto" variant="primary">{{ $permit ? 'Update Izin' : 'Buat Izin' }}</flux:button>
                      </div>
                      </fieldset>
                    </form>
                </div>
            </div>
            {{-- content end here --}}
        </div>
    </div>
</section>