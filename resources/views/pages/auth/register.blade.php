<livewire:navigation/>
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Employee Number + Auto Name -->
            <div x-data="{
                empNumber: '{{ old('employee_number') }}',
                name: '{{ old('name') }}',
                loading: false,
                error: '',
                lookup() {
                    if (!this.empNumber) return;
                    this.loading = true;
                    this.error = '';
                    fetch('{{ route('employee.lookup') }}?employee_number=' + encodeURIComponent(this.empNumber))
                        .then(r => r.json())
                        .then(data => {
                            if (data.found) {
                                this.name = data.name;
                            } else {
                                this.name = '';
                                this.error = 'Employee number not found.';
                            }
                        })
                        .catch(() => { this.error = 'Lookup failed. Try again.'; })
                        .finally(() => { this.loading = false; });
                    }
                }" class="flex flex-col gap-4">

                <flux:input
                    name="employee_number"
                    :label="__('Employee Number')"
                    type="text"
                    required
                    placeholder="EMP-0001"
                    x-model="empNumber"
                    @blur="lookup"
                    autofocus
                />
                <p x-show="error" x-text="error" class="text-sm text-red-500 -mt-2"></p>

                <!-- Auto-populated name (read-only) -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Full Name') }}</label>
                    <div class="relative">
                        <input
                            type="text"
                            name="name"
                            x-model="name"
                            readonly
                            placeholder="Auto-filled from employee record"
                            class="w-full px-3 py-2 rounded-lg border border-zinc-300 bg-zinc-50 text-zinc-700 text-sm cursor-not-allowed dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300"
                        />
                        <span x-show="loading" class="absolute right-3 top-2.5 text-xs text-zinc-400">Looking up...</span>
                    </div>
                    @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Username -->
            <flux:input
                name="username"
                :label="__('Username')"
                :value="old('username')"
                required
                autocomplete="username"
                placeholder="juan.delacruz"
            />

            

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email Address')"
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
                :label="__('Confirm Password')"
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
