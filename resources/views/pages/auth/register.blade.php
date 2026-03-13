<livewire:navigation/>
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />
           <flux:input
                name="username"
                :label="__('username')"
                :value="old('username')"
                required
                autocomplete="username"
                placeholder="nickname"
            />
            <!-- Employee Number -->
            <flux:input
                name="employee_number"
                :label="__('Employee Number')"
                :value="old('employee_number')"
                type="text"
                required
                placeholder="EMP-0001"
            />
            <!-- Role -->
            <flux:select name="role" :label="__('Role')" required>
                <option value="" disabled selected>{{ __('Select your role') }}</option>
                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>{{ __('Staff') }}</option>
                <option value="maintenance" {{ old('role') == 'maintenance' ? 'selected' : '' }}>{{ __('Maintenance') }}</option>
                <option value="inspector" {{ old('role') == 'inspector' ? 'selected' : '' }}>{{ __('Inspector') }}</option>
            </flux:select>
            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
