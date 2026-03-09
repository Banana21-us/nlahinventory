@php
    $currentLocationId = $locationId ?? request('location');
    $currentLocationName = $locationName ?? request('location_name');
    $currentPeriod = $selectedPeriod ?? request('period');
    $currentRouteName = $routeName ?? request()->route()?->getName() ?? 'Maintenance.checklist.check';
    $activePeriod = in_array($currentPeriod, ['daily', 'weekly', 'monthly'], true)
        ? $currentPeriod
        : 'daily';
    $periodUrl = fn (string $period) => route($currentRouteName, array_filter([
        'period' => $period,
        'location' => $currentLocationId,
        'location_name' => $currentLocationName,
    ], fn ($value) => $value !== null && $value !== ''));
@endphp

<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <div class="md:hidden">
            <div class="flex gap-2 overflow-x-auto pb-1">
                @foreach (['daily' => __('Daily'), 'weekly' => __('Weekly'), 'monthly' => __('Monthly')] as $periodKey => $periodLabel)
                    <a
                        href="{{ $periodUrl($periodKey) }}"
                        class="inline-flex shrink-0 items-center rounded-md border px-3 py-1.5 text-sm font-medium transition
                            {{ $activePeriod === $periodKey
                                ? 'border-sky-500 bg-sky-50 text-sky-700 dark:border-sky-500 dark:bg-sky-900/30 dark:text-sky-300'
                                : 'border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800' }}"
                    >
                        {{ $periodLabel }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="hidden md:block">
            <flux:navlist aria-label="{{ __('Checklist View') }}">
                <flux:navlist.item :href="$periodUrl('daily')" :current="$activePeriod === 'daily'">{{ __('Daily') }}</flux:navlist.item>
                <flux:navlist.item :href="$periodUrl('weekly')" :current="$activePeriod === 'weekly'">{{ __('Weekly') }}</flux:navlist.item>
                <flux:navlist.item :href="$periodUrl('monthly')" :current="$activePeriod === 'monthly'">{{ __('Monthly') }}</flux:navlist.item>
            </flux:navlist>
        </div>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        @if (filled($heading ?? null))
            <flux:heading>{{ $heading }}</flux:heading>
        @endif
        @if (filled($subheading ?? null))
            <flux:subheading>{{ $subheading }}</flux:subheading>
        @endif

        <div class=" w-full {{ ($wide ?? false) ? '' : 'max-w-lg' }}">
            {{ $slot }}
        </div>
    </div>
</div>
