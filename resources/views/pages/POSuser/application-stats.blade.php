<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
    @foreach($this->stats as $stat)
        @php
            $accents = [
                'amber'   => ['bg' => '#fffbeb', 'border' => '#fde68a', 'stroke' => '#f59e0b', 'dot_bg' => '#fef3c7'],
                'stone'   => ['bg' => '#f5f5f4', 'border' => '#e7e5e4', 'stroke' => '#78716c', 'dot_bg' => '#f5f5f4'],
                'emerald' => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'stroke' => '#16a34a', 'dot_bg' => '#dcfce7'],
                'red'     => ['bg' => '#fff1f2', 'border' => '#fecdd3', 'stroke' => '#ef4444', 'dot_bg' => '#fee2e2'],
                'blue'    => ['bg' => '#eff6ff', 'border' => '#bfdbfe', 'stroke' => '#2563eb', 'dot_bg' => '#dbeafe'],
            ];
            $a = $accents[$stat['accent']] ?? $accents['stone'];
        @endphp
        <div class="relative bg-white rounded-2xl border border-stone-200 p-5 shadow-sm overflow-hidden
                    hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">

            {{-- Background glow dot --}}
           <div class="absolute -right-3 -bottom-3 w-14 h-14 rounded-full opacity-30"
     style="background: {{ $a['dot_bg'] }}"></div>

            <div class="relative">
                {{-- Icon box --}}
                <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-4"
                     style="background: {{ $a['bg'] }}; border: 1px solid {{ $a['border'] }}">

                    @if($stat['icon'] === 'currency')
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2"
                             style="stroke: {{ $a['stroke'] }}">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                    @elseif($stat['icon'] === 'chart')
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2"
                             style="stroke: {{ $a['stroke'] }}">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>

                    @elseif($stat['icon'] === 'receipt')
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2"
                             style="stroke: {{ $a['stroke'] }}">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>

                    @elseif($stat['icon'] === 'users')
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2"
                             style="stroke: {{ $a['stroke'] }}">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>

                    @else {{-- warning --}}
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2"
                             style="stroke: {{ $a['stroke'] }}">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    @endif
                </div>

                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">{{ $stat['label'] }}</p>
                <p class="text-2xl font-black text-stone-900 tracking-tight leading-none mb-2">{{ $stat['value'] }}</p>

                <div class="flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full"
                         style="background: {{ $stat['up'] ? '#34d399' : '#f87171' }}"></div>
                    <p class="text-[11px] ms-1 font-medium text-stone-400">{{ $stat['sub'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>