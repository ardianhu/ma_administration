<section class="w-full">
    @include('partials.students-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <form wire:submit.prevent="save" class="mb-4 space-y-6"> 
                    <flux:heading size="lg">{{ __('Data Diri Santri') }}</flux:heading>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-16 lg-mb-20">
                        <div>
                            <flux:input wire:model="name" :label="__('Nama')" type="text" required autofocus autocomplete="name" />
                            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="nis" :label="__('No Induk Santri')" type="number" autofocus autocomplete="nis" />
                            @error('nis') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:select wire:model="gender" :label="__('L/P')" placeholder="Jenis Kelamin...">
                                <flux:select.option value="L">Laki-laki</flux:select.option>
                                <flux:select.option value="P">Perempuan</flux:select.option>
                            </flux:select>
                            @error('gender') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="address" :label="__('Alamat')" type="text" autofocus autocomplete="address" />
                            @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="dob" :label="__('Tempat Tanggal Lahir')" list="dob_suggestion" type="text" autofocus autocomplete="dob" />
                            @error('dob') <span class="text-red-500">{{ $message }}</span> @enderror
                            <datalist id="dob_suggestion">
                                <option value="Sumenep, ">Sumenep, </option>
                            </datalist>
                        </div>
                        <div>
                            <flux:input wire:model="th_child" :label="__('Anak ke-')" type="number" autofocus autocomplete="th_child" />
                            @error('th_child') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="siblings_count" :label="__('Jumlah Saudara')" type="number" autofocus autocomplete="siblings_count" />
                            @error('siblings_count') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>       
                    <flux:heading size="lg">{{ __('Keterangan Pendidikan') }}</flux:heading>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-16 lg-mb-20">
                        <div>
                            <flux:input wire:model="nisn" :label="__('No Induk Siswa Nasional')" type="number" autofocus autocomplete="nisn" />
                            @error('nisn') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="education" :label="__('Ijazah Terakhir')" type="text" autofocus autocomplete="education" />
                            @error('education') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="registration_date" :label="__('Diterima Tanggal')" type="date" autofocus autocomplete="registration_date" />
                            @error('registration_date') <span class="text-red-500">{{ $message }}</span> @enderror
                            <span class="text-yellow-500">Abaikan input ini jika santri mendaftar hari ini</span>
                        </div>
                    </div>
                    <flux:heading size="lg">{{ __('Keterangan Orang Tua') }}</flux:heading>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-16 lg-mb-20">
                        <div>
                            <flux:input wire:model="father_name" :label="__('Nama Ayah')" type="text" autofocus autocomplete="father_name" />
                            @error('father_name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="father_dob" :label="__('Tempat Tanggal Lahir')" list="dob_suggestion" type="text" autofocus autocomplete="fahter_dob" />
                            @error('father_dob') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="father_address" :label="__('Alamat')" type="text" autofocus autocomplete="father_address" />
                            @error('father_address') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="father_phone" :label="__('No. Telepon')" type="text" autofocus autocomplete="father_phone" />
                            @error('father_phone') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="father_education" :label="__('Pendidikan Terkahir')" type="text" autofocus autocomplete="father_education" />
                            @error('father_education') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="father_job" :label="__('Pekerjaan')" type="text" autofocus autocomplete="father_job" />
                            @error('father_job') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:select wire:model="father_alive" :label="__('Hidup/Meninggal')" placeholder="Hidup/Meninggal...">
                                <flux:select.option value="Hidup">Hidup</flux:select.option>
                                <flux:select.option value="Meninggal">Meninggal</flux:select.option>
                            </flux:select>
                            @error('father_alive') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>       
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-16 lg-mb-20">
                        <div>
                            <flux:input wire:model="mother_name" :label="__('Nama Ibu')" type="text" autofocus autocomplete="mother_name" />
                            @error('mother_name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="mother_dob" :label="__('Tempat Tanggal Lahir')" list="dob_suggestion" type="text" autofocus autocomplete="mother_dob" />
                            @error('mother_dob') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="mother_address" :label="__('Alamat')" type="text" autofocus autocomplete="mother_address" />
                            @error('mother_address') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="mother_phone" :label="__('No. Telepon')" type="text" autofocus autocomplete="mother_phone" />
                            @error('mother_phone') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="mother_education" :label="__('Pendidikan Terkahir')" type="text" autofocus autocomplete="mother_education" />
                            @error('mother_education') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="mother_job" :label="__('Pekerjaan')" type="text" autofocus autocomplete="mother_job" />
                            @error('mother_job') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:select wire:model="mother_alive" :label="__('Hidup/Meninggal')" placeholder="Hidup/Meninggal">
                                <flux:select.option value="Hidup">Hidup</flux:select.option>
                                <flux:select.option value="Meninggal">Meninggal</flux:select.option>
                            </flux:select>
                            @error('mother_alive') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <flux:heading size="lg">{{ __('Keterangan Wali') }}</flux:heading>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-16 lg-mb-20">
                        <div>
                            <flux:input wire:model="guardian_name" :label="__('Nama Wali')" type="text" autofocus autocomplete="guardian_name" />
                            @error('guardian_name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="guardian_dob" :label="__('Tempat Tanggal Lahir')" list="dob_suggestion" type="text" autofocus autocomplete="guardian_dob" />
                            @error('guardian_dob') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="guardian_address" :label="__('Alamat')" type="text" autofocus autocomplete="guardian_address" />
                            @error('guardian_address') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="guardian_phone" :label="__('No. Telepon')" type="text" autofocus autocomplete="guardian_phone" />
                            @error('guardian_phone') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="guardian_education" :label="__('Pendidikan Terkahir')" type="text" autofocus autocomplete="guardian_education" />
                            @error('guardian_education') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="guardian_job" :label="__('Pekerjaan')" type="text" autofocus autocomplete="guardian_job" />
                            @error('guardian_job') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <flux:input wire:model="guardian_relationship" :label="__('Hubungan Wali')" type="text" autofocus autocomplete="guardian_relationship" />
                            @error('guardian_relationship') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <flux:heading size="lg">{{ __('Lainnya') }}</flux:heading>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-16 lg-mb-20">
                        <div>
                            <flux:select wire:model="dorm_id" :label="__('Kamar')" placeholder="Pilih kamar...">
                                @foreach ($dorms as $dorm)
                                    <flux:select.option value="{{ $dorm->id }}">{{ $dorm->block }} - {{ $dorm->room_number }} ({{ $dorm->zone }})</flux:select.option>
                                @endforeach
                            </flux:select>
                            {{-- @error('dorm_id') <span class="text-red-500">{{ $message }}</span> @enderror --}}
                        </div>
                        <div>
                            <flux:select wire:model="islamic_class_id" :label="__('Kelas')" placeholder="Pilih kelas..." nullable>
                                @foreach ($islamicClasses as $islamicClass)
                                    <flux:select.option value="{{ $islamicClass->id }}">{{ $islamicClass->name }} - {{ $islamicClass->class }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            @error('islamic_class_id') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                  <div>
                    <flux:button type="submit" class="w-full lg:w-auto" variant="primary">{{ $student ? 'Update Santri' : 'Tambah Santri' }}</flux:button>
                  </div>
                </form>
            </div>
            {{-- content end here --}}
        </div>
    </div>
</section>