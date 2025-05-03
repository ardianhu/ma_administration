<section class="w-full">
    @include('partials.academic-years-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <form wire:submit.prevent="save" class="max-w-3xl mb-4 space-y-6">        
                    <flux:input wire:model="start" :label="__('Mulai Tgl.')" type="date" required autofocus autocomplete="start" />
                    @error('start') <span class="text-red-500">{{ $message }}</span> @enderror
                    <flux:input wire:model="end" :label="__('Sampai Tgl.')" type="date" autofocus autocomplete="end" />
                    @error('end') <span class="text-red-500">{{ $message }}</span> @enderror
                    {{-- <flux:field variant="inline"> --}}
                        {{-- <flux:checkbox wire:model="is_active" />
                        <flux:label checked>Tahun Ajaran Aktif</flux:label> --}}
                        <flux:checkbox wire:model="is_active"  value="true" label="Tahun ajaran aktif" checked />
                        @error('is_active') <span class="text-red-500">{{ $message }}</span> @enderror
                    {{-- </flux:field> --}}
                  <div>
                    <flux:button type="submit" class="w-full lg:w-auto" variant="primary">{{ $academicYear ? 'Update Tahun Ajar' : 'Buat Tahun Ajar' }}</flux:button>
                  </div>
                </form>
            </div>
            {{-- content end here --}}
        </div>
    </div>
</section>