<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ __('Checklist View') }}">
            <flux:navlist.item :href="request()->fullUrlWithQuery(['period' => 'daily'])" :current="request('period', 'daily') === 'daily'">{{ __('Daily') }}</flux:navlist.item>
            <flux:navlist.item :href="request()->fullUrlWithQuery(['period' => 'weekly'])" :current="request('period') === 'weekly'">{{ __('Weekly') }}</flux:navlist.item>
            <flux:navlist.item :href="request()->fullUrlWithQuery(['period' => 'monthly'])" :current="request('period') === 'monthly'">{{ __('Monthly') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full {{ ($wide ?? false) ? '' : 'max-w-lg' }}">
            {{ $slot }}
        </div>
    </div>
</div>
