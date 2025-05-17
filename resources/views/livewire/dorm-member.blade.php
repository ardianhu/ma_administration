<section class="w-full">
    @include('partials.dorms-heading')
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 lg:gap-6">
                    <div class="mb-4 lg:mb-0 space-y-4">
                        <div class="p-4 lg:p-8  border border-solid border-zinc-200 dark:border-zinc-700 rounded-lg space-y-4">
                            <div class="font-bold text-xl">Asrama</div>
                            <div class="space-y-2">
                                <div>Kamar : <span>{{ $dorm->block }}-{{ $dorm->room_number }}</span></div>
                                <div>Kapasitas : <span>{{ $dorm->capacity }}</span></div>
                                <div>Kawasan : <span>{{ $dorm->zone }}</span></div>
                            </div>
                        </div>
                        <div class="p-4 lg:p-8 lg:h-full border border-solid border-zinc-200 dark:border-zinc-700 rounded-lg space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="font-bold text-xl">Daftar anggota kamar</div>
                                <flux:button variant="primary" size="sm" icon="folder-arrow-down" wire:click="exportMembers()" />
                            </div>
                            <div class="space-y-2">
                                @foreach ($dorm_members as $dorm_member)
                                <div class="flex items-center justify-between">
                                    <div class="">{{ $dorm_member->name }}</div>
                                    @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'sekretaris')
                                    <flux:modal.trigger name="remove_member">
                                        <flux:button variant="danger" size="sm" icon="trash" wire:click="updateSelected({{ $dorm_member->id }})" />
                                    </flux:modal.trigger>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 col-span-2">
                        <div class="mb-4">
                            <div class="font-bold text-xl">Tambah anggota kamar</div>
                        </div>
                        <div class="mb-4">
                            <flux:input icon="magnifying-glass" class="inline" wire:model.live.debounce.300ms="search" placeholder="Cari santri"  autocomplete="off"/>
                        </div>
                        <div class="mb-4 space-y-4">
                            @if ($search != '')
                            @foreach ($students as $student)
                            <div class="flex items-center justify-between">
                                <div class="">{{ $student->name }} {{ ($student->dorm) ? $student->dorm->block : '' }}-{{ ($student->dorm) ? $student->dorm->room_number : '' }}</div>
                                @if ($student->dorm_id != $dorm->id)
                                @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'sekretaris')
                                <flux:modal.trigger name="add_member">
                                    <flux:button variant="primary" size="sm" icon="plus" wire:click="updateSelected({{ $student->id }})" />
                                </flux:modal.trigger>
                                @endif
                                @endif
                            </div>
                            @endforeach
                            @endif
                            @if ($students->count() == 0 && $search != '')
                            <div class="flex items-center justify-between">
                                <div class="text-center text-yellow-500">Santri tidak ditemukan</div>
                            </div>                                
                            @endif
                            {{-- <flux:navlist class="">
                                @if ($search != '')
                                @foreach ($students as $student)
                                    <flux:navlist.item href="#" icon="user" wire:click="updateSelectedStudent({{ $student->id }})">{{ $student->name }} {{ ($student->dorm) ? $student->dorm->block : '' }}-{{ ($student->dorm) ? $student->dorm->room_number : '' }}</flux:navlist.item>
                                @endforeach
                                @endif
                                @if ($students->count() == 0 && $search != '')
                                    <flux:navlist.item icon="exclamation-triangle">Santri tidak ditemukan</flux:navlist.item>
                                @endif
                            </flux:navlist> --}}
                        </div>
                    </div>
                </div>
            </div>
            {{-- content end here --}}
        </div>
    </div>
    <flux:modal name="remove_member" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Keluarkan santri?</flux:heading>
                <flux:text class="mt-2">
                    <p>Santri ini akan dikeluarkan dari kamar {{ $dorm->block }}-{{ $dorm->room_number }}.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="removeMmeber()">Keluarkan</flux:button>
            </div>
        </div>
    </flux:modal>
    <flux:modal name="add_member" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Tambah anggota kamar?</flux:heading>
                <flux:text class="mt-2">
                    <p>Santri ini akan dimasukkan ke kamar {{ $dorm->block }}-{{ $dorm->room_number }}.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="addMember()">Masukkan</flux:button>
            </div>
        </div>
    </flux:modal>
</section>