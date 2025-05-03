<section class="w-full">
    @include('partials.users-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <form wire:submit.prevent="save" class="max-w-3xl mb-4 space-y-6">        
                    <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />
                    @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    <flux:input wire:model="email" :label="__('Email')" type="email" required autofocus autocomplete="email" />
                    @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
                    <flux:input wire:model="password" :label="__('Password')" type="password" autofocus/>
                    @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
                    <flux:select wire:model="role_id" :label="__('Role')" placeholder="Pilih role...">
                        @foreach ($roles as $role)
                            <flux:select.option value="{{ $role->id }}">{{ $role->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                  <div>
                    <flux:button type="submit" class="w-full lg:w-auto" variant="primary">{{ $user ? 'Update User' : 'Buat User' }}</flux:button>
                  </div>
                </form>
            </div>
            {{-- content end here --}}
        </div>
    </div>
</section>