<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    @if (auth()->user()->role->name == "admin" || auth()->user()->role->name == "sekretaris")                      
                    <flux:navlist.item icon="user" :href="route('users')" :current="request()->routeIs('users')" wire:navigate>{{ __('User') }}</flux:navlist.item>
                    <flux:navlist.item icon="user" :href="route('roles')" :current="request()->routeIs('role')" wire:navigate>{{ __('Roles') }}</flux:navlist.item>
                    @endif
                </flux:navlist.group>
                <flux:navlist.group :heading="__('Data Utama')" class="grid">
                    <flux:navlist.item icon="users" :href="route('students')" :current="request()->routeIs('students')" wire:navigate>{{ __('Santri') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('students.alumni')" :current="request()->routeIs('students.alumni')" wire:navigate>{{ __('Alumni') }}</flux:navlist.item>
                    <flux:navlist.item icon="building-office" :href="route('dorms')" :current="request()->routeIs('dorms')" wire:navigate>{{ __('Asrama') }}</flux:navlist.item>
                    <flux:navlist.item icon="building-office-2" :href="route('class')" :current="request()->routeIs('class')" wire:navigate>{{ __('Kelas') }}</flux:navlist.item>
                    @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'sekretaris')
                    <flux:navlist.item icon="calendar" :href="route('academic-years')" :current="request()->routeIs('academic-years')" wire:navigate>{{ __('Tahun Ajar') }}</flux:navlist.item>
                    @endif
                </flux:navlist.group>
                <flux:navlist.group :heading="__('Perizinan')" class="grid">
                    @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'keamanan' || auth()->user()->role->name == 'kesehatan')
                    <flux:navlist.item icon="document-plus" :href="route('permits.form')" :current="request()->routeIs('permits.form')" wire:navigate>{{ __('Tambah Perizinan') }}</flux:navlist.item>
                    @endif
                    <flux:navlist.item icon="document-text" :href="route('permits', ['status' => 'active'])" :current="request()->routeIs('permits')" wire:navigate>{{ __('Daftar Perizinan') }}</flux:navlist.item>
                </flux:navlist.group>
                <flux:navlist.group :heading="__('Pelanggaran')" class="grid">
                    @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'keamanan')
                    <flux:navlist.item icon="document-plus" :href="route('violations.form')" :current="request()->routeIs('violations.form')" wire:navigate>{{ __('Tambah Pelanggaran') }}</flux:navlist.item>
                    @endif
                    <flux:navlist.item icon="document-text" :href="route('violations')" :current="request()->routeIs('violations')" wire:navigate>{{ __('Daftar Pelanggaran') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            @if (auth()->user()->role->name == "admin")  
            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>
            @endif

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
