<section class="w-full">
    @include('partials.class-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <form wire:submit.prevent="save" class="max-w-3xl mb-4 space-y-6">        
                    <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />
                    @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    <flux:input wire:model="iteration" :label="__('Iterasi/Nomor')" type="number" required autofocus autocomplete="iteration" />
                    @error('iteration') <span class="text-red-500">{{ $message }}</span> @enderror
                  <div>
                    <flux:button type="submit" class="w-full lg:w-auto" variant="primary">{{ $islamicClass ? 'Update Kelas' : 'Buat Kelas' }}</flux:button>
                  </div>
                </form>
            </div>
            {{-- content end here --}}
        </div>
    </div>
</section>