<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <flux:sidebar
            sticky="sticky"
            collapsible
            class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">

            {{-- 2. MAINTENANCE --}}
            @can('access-maintenance')
            <flux:sidebar.item
                icon="home"
                :href="route('Maintenance.dashboard')"
                :current="request()->routeIs('Maintenance.dashboard')"
                wire:navigate="wire:navigate">
                {{ __('Dashboard') }}
            </flux:sidebar.item>
            
            <flux:sidebar.item
                icon="clipboard-document-check"
                :href="route('Maintenance.checklist.check')"
                :current="request()->routeIs('Maintenance.checklist.check')"
                wire:navigate="wire:navigate">
                {{ __('Maintenance Checklist') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="calendar-days"
                :href="route('users.leaveform')"
                :current="request()->routeIs('users.leaveform')"
                wire:navigate="wire:navigate">
                {{ __('Leave') }}
            </flux:sidebar.item>
            @endcan

            {{-- INSPECTOR --}}
            @can('access-verify')
            <flux:sidebar.item
                icon="magnifying-glass"
                :href="route('Maintenance.checklist.verify')"
                :current="request()->routeIs('Maintenance.checklist.verify')"
                wire:navigate="wire:navigate">
                {{ __('Maintenance Verify') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                    icon="calendar"
                    :href="route('users.dhead-leaveform')"
                    :current="request()->routeIs('users.dhead-leaveform')"
                    wire:navigate="wire:navigate">
                    {{ __('Department Head Form') }}
            </flux:sidebar.item>
            @endcan

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

                {{-- Employee Management --}}
                <flux:sidebar.item
                    :href="route('HR.employees')"
                    :current="request()->routeIs('HR.employees')"
                    wire:navigate="wire:navigate">
                    {{ __('Employees') }}
                </flux:sidebar.item>
                
                <!-- Department Management -->
                <flux:sidebar.item
                    :href="route('HR.departments')"
                    :current="request()->routeIs('HR.departments')"
                    wire:navigate="wire:navigate">
                    {{ __('Departments') }}
                </flux:sidebar.item>

                <!-- Position Management -->
                <flux:sidebar.item
                    :href="route('HR.positions')"
                    :current="request()->routeIs('HR.positions')"
                    wire:navigate="wire:navigate">
                    {{ __('Positions') }}
                </flux:sidebar.item>

                <!-- Access Key Management -->
                <flux:sidebar.item
                    :href="route('HR.access-keys')"
                    :current="request()->routeIs('HR.access-keys')"
                    wire:navigate="wire:navigate">
                    {{ __('Access Keys') }}
                </flux:sidebar.item>

                {{-- Attendance --}}
                <flux:sidebar.item
                    :href="route('HR.attendance')"
                    :current="request()->routeIs('HR.attendance')"
                    wire:navigate="wire:navigate">
                    {{ __('Attendance') }}
                </flux:sidebar.item>

                <!-- Holidays -->
                <flux:sidebar.item
                    :href="route('HR.holidays')"
                    :current="request()->routeIs('HR.holidays')"
                    wire:navigate="wire:navigate">
                    {{ __('Holidays') }}
                </flux:sidebar.item>

                <!-- Leave Types -->
                <flux:sidebar.item
                    :href="route('HR.leave-types')"
                    :current="request()->routeIs('HR.leave-types')"
                    wire:navigate="wire:navigate">
                    {{ __('Leave Types') }}
                </flux:sidebar.item>

                <!-- Overtime Applications -->
                <flux:sidebar.item
                    :href="route('HR.overtime')"
                    :current="request()->routeIs('HR.overtime')"
                    wire:navigate="wire:navigate">
                    {{ __('Overtime') }}
                </flux:sidebar.item>

                <!-- Pay-off Applications -->
                <flux:sidebar.item
                    :href="route('HR.payoff')"
                    :current="request()->routeIs('HR.payoff')"
                    wire:navigate="wire:navigate">
                    {{ __('Pay-off') }}
                </flux:sidebar.item>

                {{-- Leave form --}}
                
            </flux:sidebar.group>
            @endcan

            {{-- ASSETS INVENTORY KUNO --}}
            <flux:sidebar.group
                class="grid"
                icon="queue-list"
                expandable="expandable"
                heading="Assets Inventory">
                <flux:sidebar.item
                    icon="square-3-stack-3d"
                    :href="route('Assetsmanagement.assets')"
                    :current="request()->routeIs('Assetsmanagement.assets')"
                    wire:navigate="wire:navigate">
                    {{ __('Assets') }}
                </flux:sidebar.item>
                <flux:sidebar.item 
                    icon="calendar-days"
                    :href="route('Assetsmanagement.transfer')"
                    :current="request()->routeIs('Assetsmanagement.transfer')"
                    wire:navigate="wire:navigate">
                    {{ __('Transfer') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    icon="clipboard-document-list"
                    :href="route('Assetsmanagement.item-entry')"
                    :current="request()->routeIs('Assetsmanagement.item-entry')"
                    wire:navigate="wire:navigate">
                    {{ __('Item Entry') }}
                </flux:sidebar.item>
                <flux:sidebar.item
                    icon="document-text"
                    :href="route('Assetsmanagement.transaction-records')"
                    :current="request()->routeIs('Assetsmanagement.transaction-records')"
                    wire:navigate="wire:navigate">
                    {{ __('Transaction Records') }}
                </flux:sidebar.item>
            </flux:sidebar.group>

            {{-- 4. PAYROLL | LABOR COMPLIANCE --}}
            @can('access-payroll')
            <!-- <flux:sidebar.item
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
            </flux:sidebar.item> -->
            
            
            <flux:sidebar.group
                icon="banknotes"
                expandable="expandable"
                heading="Payroll & Compliance"
                class="grid">
                <flux:sidebar.item
                    :href="route('HR.payroll-compliance')"
                    :current="request()->routeIs('HR.payroll-compliance')"
                    wire:navigate="wire:navigate">
                    {{ __('Shift Differential') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            @endcan

            {{-- Cashier Section --}}
             @can('access-cashier-only')
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
            @endcan

            <flux:spacer/>
            
            {{-- Medical Records Link --}}
            <!-- <flux:sidebar.item
                icon="shopping-cart"
                href="http://192.168.2.200:3777/medical.online"
                target="_blank">
                {{ __('Medical Records') }}
            </flux:sidebar.item> -->
            
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

        <!-- Top Header (all screen sizes) -->
        <flux:header class="border-b border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle icon="bars-2" inset="left"/>
            <flux:spacer/>

            {{-- Only show the profile dropdown if the user is logged in --}}
            @auth
            <flux:dropdown position="top" align="end" class="lg:hidden">
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
            <flux:button :href="route('login')" variant="ghost" size="sm" class="lg:hidden">Log in</flux:button>
            @endauth
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
