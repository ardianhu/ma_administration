<section class="w-full">
    @include('partials.roles-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <form wire:submit.prevent="save">
                  <div class="mb-4">
                    {{-- <label for="name" class="text-gray-700 mb-2 block font-bold">Name</label>
                    <input
                      wire:model="name"
                      type="text"
                      id="name"
                      class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
                    /> --}}
                    
                    <flux:input wire:model="name" :label="__('Nama')" type="text" required autofocus autocomplete="name" />
                    @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                  </div>
                  <div>
                    {{-- <button
                      type="submit"
                      class="bg-zinc-500 hover:bg-zinc-700 text-white font-bold py-2 px-4 rounded"
                    >
                      {{ $role ? 'Update Role' : 'Create Role' }}
                    </button> --}}
                    <flux:button type="submit" class="w-full lg:w-auto" variant="primary">{{ $role ? 'Update Role' : 'Buat Role' }}</flux:button>
                  </div>
                </form>
            </div>
            {{-- content end here --}}
        </div>
    </div>
</section>
  