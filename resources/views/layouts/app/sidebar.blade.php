<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <flux:sidebar
            sticky="sticky"
            collapsible="mobile"
            class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">

            {{-- 1. MEDICAL MISSION --}}
            @can('access-medical')
            <flux:sidebar.header>
                <x-app-logo
                    :sidebar="true"
                    href="{{ route('medmission.dashboard') }}"
                    wire:navigate="wire:navigate"/>
                <flux:sidebar.collapse class="lg:hidden"/>
            </flux:sidebar.header>

            <flux:sidebar.group
                icon="home"
                expandable="expandable"
                heading="Medical Mission"
                class="grid">
                <flux:sidebar.item
                    :href="route('medmission.dashboard')"
                    :current="request()->routeIs('medmission.dashboard')"
                    wire:navigate="wire:navigate">
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    :href="route('medmission.medicines')"
                    :current="request()->routeIs('medmission.medicines')"
                    wire:navigate="wire:navigate">
                    {{ __('Medicines') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    :href="route('medmission.dispense')"
                    :current="request()->routeIs('medmission.dispense')"
                    wire:navigate="wire:navigate">
                    {{ __('Dispense Medicine') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    :href="route('medmission.patients')"
                    :current="request()->routeIs('medmission.patients')"
                    wire:navigate="wire:navigate">
                    {{ __('Patients') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            @endcan

            {{-- 2. CHECKLIST --}}
            {{-- Visible if user is Maintenance, Inspector, OR HR --}}
            @if(Gate::allows('access-maintenance') || Gate::allows('access-verify'))
            <flux:sidebar.group
                icon="clipboard-check"
                expandable="expandable"
                heading="Checklist"
                class="grid">
                @can('access-maintenance')
                <flux:sidebar.item
                    :href="route('Maintenance.checklist.check')"
                    :current="request()->routeIs('Maintenance.checklist.check')"
                    wire:navigate="wire:navigate">
                    {{ __('Maintenance') }}
                </flux:sidebar.item>
                @endcan 
                @can('access-verify')
                <flux:sidebar.item
                    :href="route('Maintenance.checklist.verify')"
                    :current="request()->routeIs('Maintenance.checklist.verify')"
                    wire:navigate="wire:navigate">
                    {{ __('Verify') }}
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endif

            {{-- 3. HR CORNER WITH DROPDOWN --}}
            @can('access-hr-only')
            <flux:sidebar.item
                        icon="newspaper"
                        :href="route('NewsPage.newshr')"
                        :current="request()->routeIs('NewsPage.newshr')"
                        wire:navigate="wire:navigate">
                        {{ __('News') }}
                    </flux:sidebar.item>
            <flux:sidebar.group
                icon="users"
                expandable="expandable"
                heading="HR Corner"
                class="grid">
                
                {{-- HR Dashboard --}}
                <flux:sidebar.item
                    :href="route('HR.hrdashboard')"
                    :current="request()->routeIs('HR.hrdashboard')"
                    wire:navigate="wire:navigate">
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
                
                {{-- Leave Applications --}}
                <flux:sidebar.item
                    :href="route('HR.hr-leave-management')"
                    :current="request()->routeIs('HR.hr-leave-management')"
                    wire:navigate="wire:navigate">
                    {{ __('Leave Applications') }}
                </flux:sidebar.item>

                
                {{-- Employee List --}}
                <flux:sidebar.item
                    :href="route('HR.userlist')"
                    :current="request()->routeIs('HR.userlist')"
                    wire:navigate="wire:navigate">
                    {{ __('Web User List') }}
                </flux:sidebar.item>

                {{-- Leave form --}}
            </flux:sidebar.group>
            @endcan

            <flux:sidebar.item
                    icon="calendar"
                    :href="route('users.leaveform')"
                    :current="request()->routeIs('users.leaveform')"
                    wire:navigate="wire:navigate">
                    {{ __('Leave Form') }}
            </flux:sidebar.item>
            
            <flux:sidebar.item
                    icon="calendar"
                    :href="route('users.dhead-leaveform')"
                    :current="request()->routeIs('users.dhead-leaveform')"
                    wire:navigate="wire:navigate">
                    {{ __('Department Head Form') }}
            </flux:sidebar.item>

            {{-- Cashier Section --}}
            <flux:sidebar.group
                class="grid"
                icon="currency-dollar"
                expandable="expandable"
                heading="Cashier">
                <flux:sidebar.item
                    icon="clipboard-document"
                    :href="route('pos.dashboard')"
                    :current="request()->routeIs('pos.dashboard')"
                    wire:navigate="wire:navigate">
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    icon="banknotes"
                    :href="route('pos.main')"
                    :current="request()->routeIs('pos.main')"
                    wire:navigate="wire:navigate">
                    {{ __('POS') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    icon="queue-list"
                    :href="route('pos.inventory')"
                    :current="request()->routeIs('pos.inventory')"
                    wire:navigate="wire:navigate">
                    {{ __('Inventory') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    icon="square-3-stack-3d"
                    :href="route('pos.items')"
                    :current="request()->routeIs('pos.items')"
                    wire:navigate="wire:navigate">
                    {{ __('Items') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    icon="printer"
                    :href="route('pos.sales')"
                    :current="request()->routeIs('pos.sales')"
                    wire:navigate="wire:navigate">
                    {{ __('Sales') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    icon="user-group"
                    :href="route('pos.customers')"
                    :current="request()->routeIs('pos.customers')"
                    wire:navigate="wire:navigate">
                    {{ __('Customers') }}
                </flux:sidebar.item>
            </flux:sidebar.group>

            <flux:spacer/>
            
            {{-- Medical Records Link --}}
            <flux:sidebar.item
                icon="shopping-cart"
                href="http://192.168.2.200:3777/medical.online"
                target="_blank">
                {{ __('Medical Records') }}
            </flux:sidebar.item>
            
            <!-- <flux:sidebar.nav> <flux:sidebar.item icon="folder-git-2"
            href="https://github.com/laravel/livewire-starter-kit" target="_blank"> {{
            __('Repository') }} </flux:sidebar.item> <flux:sidebar.item
            icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
            target="_blank"> {{ __('Documentation') }} </flux:sidebar.item>
            </flux:sidebar.nav> -->

            <x-desktop-user-menu
                class="hidden lg:block"
                :name="auth()->user()?->name ?? 'Guest'"/>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>
            <flux:spacer/>

            {{-- Only show the profile dropdown if the user is logged in --}}
            @auth
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"/>

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"/>
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator/>
                    <flux:menu.item
                        :href="route('profile.edit')"
                        icon="cog"
                        wire:navigate="wire:navigate">{{ __('Settings') }}</flux:menu.item>
                    <flux:menu.separator/>

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer">
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