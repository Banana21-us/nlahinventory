<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible  class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('medmission.dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>
            
            <flux:sidebar.group icon="home" expandable heading="Medical Mission" class="grid">
                <flux:sidebar.item :heading="__('Platform')" class="grid">
                    <flux:sidebar.item  :href="route('medmission.dashboard')" :current="request()->routeIs('medmission.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.item>

                <flux:sidebar.item  class="grid">
                    <flux:sidebar.item  :href="route('medicines')" :current="request()->routeIs('medicines')" wire:navigate>
                        {{ __('Medicines') }}
                    </flux:sidebar.item>
                </flux:sidebar.item>

                <flux:sidebar.item  class="grid">
                    <flux:sidebar.item  :href="route('dispense')" :current="request()->routeIs('dispense')" wire:navigate>
                        {{ __('Dispense Medicine') }}
                    </flux:sidebar.item>
                </flux:sidebar.item>

                <flux:sidebar.item  class="grid">
                    <flux:sidebar.item :href="route('patients')" :current="request()->routeIs('patients')" wire:navigate>
                        {{ __('Patients') }}
                    </flux:sidebar.item>
                </flux:sidebar.item>
                
            </flux:sidebar.group>


            <flux:sidebar.group icon="clipboard-check" expandable heading="Checklist" class="grid">
                <flux:sidebar.item :heading="__('Platform')" class="grid">
                    <flux:sidebar.item  :href="route('Maintenance.checklist.check')" :current="request()->routeIs('Maintenance.checklist.check')" wire:navigate>
                        {{ __('Maintenance') }}
                    </flux:sidebar.item>
                </flux:sidebar.item>

                <flux:sidebar.item class="grid">
                    <flux:sidebar.item  :href="route('Maintenance.checklist.verify')" :current="request()->routeIs('Maintenance.checklist.verify')" wire:navigate>
                        {{ __('Verify') }}
                    </flux:sidebar.item>
                </flux:sidebar.item>   
            </flux:sidebar.group>

                <flux:sidebar.group>
                    <flux:sidebar.item icon="newspaper" heading="Checklist" class :href="route('NewsPage.newshr')" :current="request()->routeIs('NewsPage.newshr')" wire:navigate>
                        {{ __('News') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

            <flux:sidebar.group  icon="users" expandable heading="HR Corner" class="grid">
                <flux:sidebar.item :heading="__('Platform')" class="grid">
                    <flux:sidebar.item :href="route('HR.hrdashboard')" :current="request()->routeIs('HR.hrdashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.item>
                <flux:sidebar.item class="grid">
                    <flux:sidebar.item :href="route('HR.userlist')" :current="request()->routeIs('HR.userlist')" wire:navigate>
                        {{ __('Employees') }}
                    </flux:sidebar.item>
                </flux:sidebar.item>
                <flux:sidebar.item class="grid">
                    <flux:sidebar.item :href="route('HR.leave-applications')" :current="request()->routeIs('HR.leave-applications')" wire:navigate>
                        {{ __('Leave Applications') }}
                    </flux:sidebar.item>
                </flux:sidebar.item>
                <flux:sidebar.item class="grid">
                    <flux:sidebar.item :href="route('HR.leave-applications')" :current="request()->routeIs('HR.leave-applications')" wire:navigate>
                        {{ __('Leave Form') }}
                    </flux:sidebar.item>
                </flux:sidebar.item>
            </flux:sidebar.group>

            <flux:sidebar.group icon="clipboard-check" expandable heading="POS" class="grid">
                <flux:sidebar.item :heading="__('Platform')" class="grid">
                    <flux:sidebar.item  :href="route('POS.posproducts')" :current="request()->routeIs('POS.posproducts')" wire:navigate>
                        {{ __('Products') }}
                    </flux:sidebar.item>
                </flux:sidebar.item> 
            </flux:sidebar.group>
            

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()?->name ?? 'Guest'" />
        </flux:sidebar>
        <!-- Mobile User Menu -->
        <flux:header>
    <flux:sidebar.toggle icon="bars-2" inset="left" />
    <flux:spacer />

    {{-- Only show the profile dropdown if the user is logged in --}}
    @auth
        <flux:dropdown position="top" align="end">
            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down"
            />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar
                                :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                            />
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />
                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    @else
        {{-- Show a Login button if guest --}}
        <flux:button :href="route('login')" variant="ghost" size="sm">Log in</flux:button>
    @endauth
</flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
