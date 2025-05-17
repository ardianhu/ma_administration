    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:input wire:model="dashboard_search" placeholder="Cari santri">
            <x-slot name="iconTrailing">
                <flux:button size="sm" variant="subtle" icon="magnifying-glass" wire:click="dashboardSearch()" class="-mr-1" />
            </x-slot>
        </flux:input>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="p-6 relative aspect-video overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-blue-100 via-blue-50 to-white dark:from-blue-900/30 dark:via-blue-800/20 dark:to-neutral-900 shadow-sm flex flex-col justify-between">
                <div class="text-sm font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wide">Total Santri</div>
                <div class="text-4xl font-bold text-blue-900 dark:text-blue-100 mt-2">{{ $studentsCount }}</div>
                <div class="absolute right-4 bottom-4 opacity-10 text-7xl font-black select-none pointer-events-none">👥</div>
            </div>
            <div class="p-6 relative aspect-video overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-green-100 via-green-50 to-white dark:from-green-900/30 dark:via-green-800/20 dark:to-neutral-900 shadow-sm flex flex-col justify-between">
                <div class="text-sm font-semibold text-green-700 dark:text-green-300 uppercase tracking-wide">Total Santri Putra</div>
                <div class="text-4xl font-bold text-green-900 dark:text-green-100 mt-2">{{ $maleStudentsCount }}</div>
                <div class="absolute right-4 bottom-4 opacity-10 text-7xl font-black select-none pointer-events-none">🧑‍🦱</div>
            </div>
            <div class="p-6 relative aspect-video overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-pink-100 via-pink-50 to-white dark:from-pink-900/30 dark:via-pink-800/20 dark:to-neutral-900 shadow-sm flex flex-col justify-between">
                <div class="text-sm font-semibold text-pink-700 dark:text-pink-300 uppercase tracking-wide">Total Santri Putri</div>
                <div class="text-4xl font-bold text-pink-900 dark:text-pink-100 mt-2">{{ $femaleStudentsCount }}</div>
                <div class="absolute right-4 bottom-4 opacity-10 text-7xl font-black select-none pointer-events-none">🧕</div>
            </div>    </div>    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
