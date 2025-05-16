<section class="w-full">
    @include('partials.dorms-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <form wire:submit.prevent="save" class="max-w-3xl mb-4 space-y-6">        
                    <flux:input wire:model="block" :label="__('Blok')" type="text" required autofocus autocomplete="block" />
                    @error('block') <span class="text-red-500">{{ $message }}</span> @enderror
                    <flux:input wire:model="room_number" :label="__('Kamar')" type="number" required autofocus autocomplete="room_number" />
                    @error('room_number') <span class="text-red-500">{{ $message }}</span> @enderror
                    <flux:input wire:model="capacity" :label="__('Kapasitas')" type="number" required autofocus autocomplete="capacity"/>
                    @error('capacity') <span class="text-red-500">{{ $message }}</span> @enderror
                    <flux:select wire:model="zone" :label="__('Kawasan')" placeholder="Putra/Putri">
                        <flux:select.option value="putra">Putra</flux:select.option>
                        <flux:select.option value="putri">Putri</flux:select.option>
                    </flux:select>
                    @error('zone') <span class="text-red-500">{{ $message }}</span> @enderror
                  <div>
                    <flux:button type="submit" class="w-full lg:w-auto" variant="primary">{{ $dorm ? 'Update Asrama' : 'Buat Asrama' }}</flux:button>
                  </div>
                </form>
            </div>
            {{-- content end here --}}
        </div>
    </div>
</section>