<x-layouts::app :title="__('Checklist')">
    <section class="w-full">
        <x-pages::checklist.layout :heading="__('Checklist')" :subheading="__('Checklist page')">
            <div class="space-y-2">
                <flux:text>{{ __('Checklist content goes here.') }}</flux:text>
            </div>
        </x-pages::checklist.layout>
    </section>
</x-layouts::app>
