<section class="w-full">
    @include('partials.class-heading')
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
                                <div>Tingkat : <span>{{ $islamic_class->name }}-{{ $islamic_class->class }}<sup>{{ $islamic_class->sub_class }}</sup></span></div>
                            </div>
                        </div>
                        <div class="p-4 lg:p-8 lg:h-full border border-solid border-zinc-200 dark:border-zinc-700 rounded-lg space-y-4">
                            <div class="font-bold text-xl">Daftar murid</div>
                            <div class="space-y-2">
                                @foreach ($islamic_class_members as $islamic_class_member)
                                <div class="flex items-center justify-between">
                                    <div class="">{{ $islamic_class_member->name }}</div>
                                    <flux:modal.trigger name="remove_member">
                                        <flux:button variant="danger" size="sm" icon="trash" wire:click="updateSelected({{ $islamic_class_member->id }})" />
                                    </flux:modal.trigger>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 col-span-2">
                        <div class="mb-4">
                            <div class="font-bold text-xl">Tambah murid</div>
                        </div>
                        <div class="mb-4">
                            <flux:input icon="magnifying-glass" class="inline" wire:model.live.debounce.300ms="search" placeholder="Cari santri" autocomplete="off" />
                        </div>
                        <div class="mb-4 space-y-4">
                            @if ($search != '')
                            @foreach ($students as $student)
                            <div class="flex items-center justify-between">
                                <div class="">{{ $student->name }} {{ ($student->islamicClass) ? $student->islamicClass->name : '' }}-{{ ($student->islamicClass) ? $student->islamicClass->class : '' }}<sup>{{ ($student->islamicClass) ? $student->islamicClass->sub_class : '' }}</sup></div>
                                @if ($student->islamic_class_id != $islamic_class->id)
                                <flux:modal.trigger name="add_member">
                                    <flux:button variant="primary" size="sm" icon="plus" wire:click="updateSelected({{ $student->id }})" />
                                </flux:modal.trigger>
                                @endif
                            </div>
                            @endforeach
                            @endif
                            @if ($students->count() == 0 && $search != '')
                            <div class="flex items-center justify-between">
                                <div class="text-center text-yellow-500">Santri tidak ditemukan</div>
                            </div>                                
                            @endif
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
                    <p>Santri ini akan dikeluarkan dari Kelas {{ $islamic_class->name }}-{{ $islamic_class->class }}<sup>{{ $islamic_class->sub_class }}</sup>.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="removeMember()">Keluarkan</flux:button>
            </div>
        </div>
    </flux:modal>
    <flux:modal name="add_member" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Tambah anggota kamar?</flux:heading>
                <flux:text class="mt-2">
                    <p>Santri ini akan dimasukkan ke kelas {{ $islamic_class->name }}-{{ $islamic_class->class }}<sup>{{ $islamic_class->sub_class }}</sup>.</p>
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