<div class="flex flex-col items-center justify-center min-h-screen">
    <flux:card class="w-full max-w-md p-8 space-y-6">

        <div class="text-center">
            <flux:icon name="envelope" class="mx-auto size-12 text-blue-500" />
            <flux:heading size="xl" class="mt-4">Verify your email</flux:heading>
            <flux:subheading class="mt-2">
                We sent a verification link to
                <strong>{{ auth()->user()->email }}</strong>.
                Please check your inbox.
            </flux:subheading>
        </div>

        @if($sent)
            <flux:callout variant="success" icon="check-circle">
                A new verification link has been sent to your email!
            </flux:callout>
        @endif

        <flux:button wire:click="resend" variant="primary" class="w-full">
            Resend Verification Email
        </flux:button>

        <div class="text-center">
            <flux:link href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Log out
            </flux:link>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>

    </flux:card>
</div>