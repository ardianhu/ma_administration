<section class="w-full">
    @include('partials.class-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <form wire:submit.prevent="save" class="max-w-3xl mb-4 space-y-6">        
                    <flux:input wire:model="name" :label="__('Tingkat')" type="text" required autofocus autocomplete="name" />
                    @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    <flux:input wire:model="class" :label="__('Kelas')" type="number" required autofocus autocomplete="class" />
                    @error('class') <span class="text-red-500">{{ $message }}</span> @enderror
                    <flux:input wire:model="sub_class" :label="__('Sub Kelas')" type="number" required autofocus autocomplete="sub_class" />
                    @error('sub_class') <span class="text-red-500">{{ $message }}</span> @enderror
                  <div>
                    <flux:button type="submit" class="w-full lg:w-auto" variant="primary">{{ $islamicClass ? 'Update Kelas' : 'Buat Kelas' }}</flux:button>
                  </div>
                </form>
            </div>
            {{-- content end here --}}
        </div>
    </div>
</section>