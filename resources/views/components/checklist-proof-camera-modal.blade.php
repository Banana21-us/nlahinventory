    @props([
        'areaParts' => [],
        'selectedSlots' => [],
        'selectedLocation' => '',
        'selectedDate' => null,
        'periodType' => 'daily',
        'activePeriodKey' => 'selected',
    ])

    @php
        $dayKey = $periodType === 'daily' ? 'selected' : $activePeriodKey;
        
        // Debug - remove after fixing
        $hasAreaParts = count($areaParts) > 0;
        $areaPartsCount = count($areaParts);
        $defaultProofPayload = [
            'frequency' => $periodType,
            'dayKey' => $dayKey,
            'dateLabel' => $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('M d, Y') . ' | ' . \Carbon\Carbon::now('Asia/Manila')->format('H:i') : '',
            'location' => $selectedLocation,
            'capturedBy' => auth()->user()?->name ?? '',
        ];
    @endphp

    <div id="proofCaptureModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-3" wire:ignore.self>
        <div class="w-full max-w-sm bg-white shadow-2xl dark:bg-zinc-900 rounded-xl">
            <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-700">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Capture Proof Photo') }}</h3>
                <span id="proofAreaCounter" class="text-xs text-zinc-500 dark:text-zinc-400"></span>
            </div>
            <div class="p-4">
                <div class="flex flex-col gap-3">

                    {{-- Top bar: Area label + AM/PM toggle --}}
                    <div class="flex items-center justify-between gap-2">
                        <div id="proofCurrentAreaName" class="flex-1 rounded-md border border-sky-200 bg-sky-50 px-3 py-1.5 text-sm font-semibold text-sky-700 dark:border-sky-700 dark:bg-sky-900/30 dark:text-sky-300 truncate">
                            —
                        </div>
                        <div class="hidden flex items-center gap-1 rounded-full border border-zinc-200 bg-white p-0.5 text-[11px] dark:border-zinc-700 dark:bg-zinc-900 shrink-0" role="group" aria-label="{{ __('Shift') }}" data-shift-group>
                            <button type="button" class="js-shift-toggle rounded-full px-2 py-0.5 font-semibold bg-white text-zinc-700 hover:bg-zinc-100 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800" data-shift="AM" aria-pressed="false">AM</button>
                            <button type="button" class="js-shift-toggle rounded-full px-2 py-0.5 font-semibold bg-white text-zinc-700 hover:bg-zinc-100 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800" data-shift="PM" aria-pressed="false">PM</button>
                        </div>
                    </div>

                    {{-- Step indicator — 5 per row --}}
                    <div id="proofStepIndicator" class="flex flex-wrap gap-2 justify-start">
                        @forelse ($areaParts as $index => $part)
                            @php
                                $amChecked = false;
                                $pmChecked = false;
                                if ($periodType === 'daily') {
                                    $amKey = $part['id'].'|'.($periodType === 'daily' ? 'selected' : '').'|AM';
                                    $pmKey = $part['id'].'|'.($periodType === 'daily' ? 'selected' : '').'|PM';
                                    $amChecked = isset($selectedSlots[$amKey]);
                                    $pmChecked = isset($selectedSlots[$pmKey]);
                                } else {
                                    foreach (array_keys($selectedSlots) as $slotKey) {
                                        if (str_starts_with($slotKey, $part['id'].'|')) {
                                            $amChecked = true;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            <div
                                class="js-proof-area-item flex flex-col items-center gap-1"
                                style="width: calc(20% - 0.4rem)"
                                data-part-id="{{ $part['id'] }}"
                                data-day-key="{{ $dayKey }}"
                                data-has-am="{{ $amChecked ? '1' : '0' }}"
                                data-has-pm="{{ $pmChecked ? '1' : '0' }}"
                                data-area-part="{{ $part['display_name'] }}"
                                data-location="{{ $selectedLocation }}"
                                data-frequency="{{ $periodType }}"
                                data-date-label="{{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('M d, Y') : '' }}"
                                data-captured-by="{{ auth()->user()?->name ?? '' }}"
                                data-step-index="{{ $index }}"
                            >
                                <div data-area-status class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-zinc-300 bg-zinc-100 text-xs font-bold text-zinc-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 transition-all">
                                    {{ $index + 1 }}
                                </div>
                                <span class="text-[9px] text-center text-zinc-500 dark:text-zinc-400 leading-tight line-clamp-2 w-full">{{ $part['display_name'] }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-zinc-400 py-2">{{ __('No area parts available') }}</p>
                        @endforelse
                    </div>

                    {{-- Camera --}}
                    <div class="w-full space-y-3">
                        <div class="relative aspect-square w-full overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                            <video id="proofVideo" class="h-full w-full rounded-lg bg-black object-cover"></video>
                            <img id="proofPreview" class="hidden h-full w-full rounded-lg bg-black object-cover" alt="{{ __('Proof preview') }}">
                        </div>

                        <div class="flex justify-center">
                            <button type="button" id="proofCaptureOverlayBtn"
                                class="inline-flex aspect-square items-center justify-center rounded-full border-2 border-zinc-300 bg-zinc-500 p-0 leading-none text-white shadow-[0_6px_14px_rgba(0,0,0,0.35)] transition hover:scale-105 hover:bg-zinc-400"
                                style="width:56px;height:56px;min-width:56px;min-height:56px;max-width:56px;max-height:56px;border-radius:9999px;"
                                aria-label="{{ __('Capture photo') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <canvas id="proofCanvas" class="hidden"></canvas>
                        <p id="proofError" class="hidden rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700"></p>

                        <div class="flex flex-wrap items-center justify-between gap-2">
                           <input type="text" id="proofCommentInput" placeholder="{{ __('Add comment (optional)...') }}"
                            class="min-w-0 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm text-zinc-700 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100" />
                            <div class="ms-auto flex items-center gap-2">
                                <button type="button" id="proofDiscardBtn" class="hidden rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">X</button>
                                <button type="button" id="proofConfirmBtn" class="hidden rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">{{ __('Confirm') }}</button>
                                <button type="button" id="proofCancelBtn" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">{{ __('Cancel') }}</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

  @once
<script>
(() => {
    const DEFAULT_PROOF_PAYLOAD = @json($defaultProofPayload);
    const getSystemShift = () => new Date().getHours() >= 12 ? 'PM' : 'AM';
    let stream = null;
    let activeShift = getSystemShift();
    let isOpeningModal = false;
    let currentIndex = 0;
    let capturedMap = {};
    let areaQueue = [];
    let usingFallback = false;
    let modal, video, preview, canvas, errorBox, commentInput,
        captureOverlayBtn, discardBtn, confirmBtn, cancelBtn,
        areaNameDisplay, areaCounter;

    const bindElements = () => {
        modal             = document.getElementById('proofCaptureModal');
        video             = document.getElementById('proofVideo');
        preview           = document.getElementById('proofPreview');
        canvas            = document.getElementById('proofCanvas');
        errorBox          = document.getElementById('proofError');
        commentInput      = document.getElementById('proofCommentInput');
        captureOverlayBtn = document.getElementById('proofCaptureOverlayBtn');
        discardBtn        = document.getElementById('proofDiscardBtn');
        confirmBtn        = document.getElementById('proofConfirmBtn');
        cancelBtn         = document.getElementById('proofCancelBtn');
        areaNameDisplay   = document.getElementById('proofCurrentAreaName');
        areaCounter       = document.getElementById('proofAreaCounter');
        return !!(modal && video && preview && canvas);
    };

    const isSecureContext = () => {
        return window.isSecureContext ||
               location.protocol === 'https:' ||
               location.hostname === 'localhost' ||
               location.hostname === '127.0.0.1';
    };

    // ─── Fallback file input (works on HTTP) ──────────────────────────────────
    const ensureFallbackInput = () => {
        let fi = document.getElementById('proofFallbackInput');
        if (fi) return fi;
        fi = document.createElement('input');
        fi.type = 'file';
        fi.accept = 'image/*';
        fi.capture = 'environment';
        fi.id = 'proofFallbackInput';
        fi.style.cssText = 'position:fixed;opacity:0;pointer-events:none;top:0;left:0;width:1px;height:1px;';
        document.body.appendChild(fi);

        fi.addEventListener('change', async (e) => {
            const file = e.target.files?.[0];
            fi.value = '';
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (ev) => {
                const img = new Image();
                img.onload = () => {
                    canvas.width = 720;
                    canvas.height = 720;
                    const ctx = canvas.getContext('2d');
                    const ss = Math.min(img.width, img.height);
                    const sx = Math.floor((img.width  - ss) / 2);
                    const sy = Math.floor((img.height - ss) / 2);
                    ctx.drawImage(img, sx, sy, ss, ss, 0, 0, 720, 720);
                    handleCaptureFromCanvas(ctx);
                };
                img.src = ev.target.result;
            };
            reader.readAsDataURL(file);
        });
        return fi;
    };

    const useFallbackMode = () => {
        usingFallback = true;
        video.classList.add('hidden');

        // Show a tap-to-photo placeholder instead of video
        const placeholder = document.getElementById('proofFallbackPlaceholder') || (() => {
            const d = document.createElement('div');
            d.id = 'proofFallbackPlaceholder';
            d.className = 'flex flex-col items-center justify-center w-full h-full gap-2 text-zinc-400 dark:text-zinc-500';
            d.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-xs font-medium">Tap button to take photo</span>
                <span class="text-[10px] text-zinc-400">(HTTP mode — no live preview)</span>
            `;
            video.parentElement.appendChild(d);
            return d;
        })();

        placeholder.classList.remove('hidden');
        preview.classList.add('hidden');
        setError('');
    };

    // ─── Secure context: real camera ─────────────────────────────────────────
    const SHIFT_ACTIVE_CLASSES   = ['bg-zinc-800','text-white','dark:bg-zinc-100','dark:text-zinc-900','shadow-inner'];
    const SHIFT_INACTIVE_CLASSES = ['bg-white','text-zinc-700','dark:bg-zinc-900','dark:text-zinc-200'];

    const setError = (msg) => {
        if (!errorBox) return;
        if (!msg) { errorBox.textContent = ''; errorBox.classList.add('hidden'); return; }
        errorBox.textContent = msg;
        errorBox.classList.remove('hidden');
    };

    const stopCamera = () => {
        if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
        if (video) video.srcObject = null;
    };

    const startCamera = async () => {
        // HTTP / insecure context — skip getUserMedia entirely
        if (!isSecureContext() || !navigator.mediaDevices?.getUserMedia) {
            useFallbackMode();
            return;
        }

        stopCamera();
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: 'environment' }, width: { ideal: 720 }, height: { ideal: 720 }, aspectRatio: { ideal: 1 } },
                audio: false
            });
            video.srcObject = stream;
            await video.play();
            video.classList.remove('hidden');
            const ph = document.getElementById('proofFallbackPlaceholder');
            if (ph) ph.classList.add('hidden');
            usingFallback = false;
            setError('');
        } catch (e) {
            // Camera permission denied even on HTTPS — fall back
            useFallbackMode();
        }
    };

    const getChecklistComponent = () => {
        const root = modal?.closest('[wire\\:id]');
        return root ? window.Livewire?.find(root.getAttribute('wire:id')) : null;
    };

    const buildQueue = () => {
        if (!modal) return [];
        return Array.from(modal.querySelectorAll('.js-proof-area-item'));
    };

    const setActiveShift = (shift) => {
        const normalized = (shift || getSystemShift()).toUpperCase();
        activeShift = normalized;
        document.querySelectorAll('.js-shift-toggle').forEach((btn) => {
            const isActive = btn.getAttribute('data-shift') === normalized;
            btn.classList.remove(...SHIFT_ACTIVE_CLASSES, ...SHIFT_INACTIVE_CLASSES);
            btn.classList.add(...(isActive ? SHIFT_ACTIVE_CLASSES : SHIFT_INACTIVE_CLASSES));
            btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
        capturedMap = {};
        areaQueue = buildQueue();
        areaQueue.forEach(item => {
            const hasAm = item.dataset.hasAm === '1';
            const hasPm = item.dataset.hasPm === '1';
            const alreadyDone = normalized === 'PM' ? hasPm : hasAm;
            if (alreadyDone) capturedMap[item.dataset.partId] = true;
        });
        let firstUncaptured = 0;
        while (firstUncaptured < areaQueue.length && capturedMap[areaQueue[firstUncaptured]?.dataset?.partId]) {
            firstUncaptured++;
        }
        currentIndex = firstUncaptured;
        if (areaNameDisplay) {
            if (currentIndex >= areaQueue.length) {
                areaNameDisplay.textContent = '✓ All areas captured!';
                captureOverlayBtn?.classList.add('opacity-40', 'pointer-events-none');
            } else {
                areaNameDisplay.textContent = areaQueue[currentIndex]?.dataset?.areaPart ?? '—';
                if (commentInput) commentInput.placeholder = `Add comment for ${areaQueue[currentIndex]?.dataset?.areaPart ?? 'area'}...`;
                captureOverlayBtn?.classList.remove('opacity-40', 'pointer-events-none');
            }
        }
        if (areaCounter) areaCounter.textContent = `${Math.min(currentIndex + 1, areaQueue.length)}/${areaQueue.length}`;
        refreshAreaListUI();
    };

    const refreshAreaListUI = () => {
        areaQueue.forEach((item, idx) => {
            const partId   = item.dataset.partId;
            const statusEl = item.querySelector('[data-area-status]');
            const isCurrent = idx === currentIndex;
            const isDone    = !!capturedMap[partId];
            if (!statusEl) return;
            if (isDone) {
                statusEl.className = 'flex h-8 w-8 items-center justify-center rounded-full border-2 border-emerald-500 bg-emerald-500 text-xs font-bold text-white transition-all';
                statusEl.innerHTML = `<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0L3.293 9.207a1 1 0 011.414-1.414l3.043 3.043 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>`;
            } else if (isCurrent) {
                statusEl.className = 'flex h-8 w-8 items-center justify-center rounded-full border-2 border-sky-500 bg-white text-xs font-bold text-sky-600 dark:bg-zinc-900 transition-all';
                statusEl.textContent = String(idx + 1);
            } else {
                statusEl.className = 'flex h-8 w-8 items-center justify-center rounded-full border-2 border-zinc-300 bg-zinc-100 text-xs font-bold text-zinc-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 transition-all';
                statusEl.textContent = String(idx + 1);
            }
        });
    };

    const goToIndex = (idx) => {
        areaQueue = buildQueue();
        let next = idx;
        while (next < areaQueue.length && capturedMap[areaQueue[next]?.dataset?.partId]) {
            next++;
        }
        currentIndex = next;
        if (currentIndex >= areaQueue.length) {
            if (areaNameDisplay) areaNameDisplay.textContent = '✓ All areas captured!';
            if (areaCounter) areaCounter.textContent = `${areaQueue.length}/${areaQueue.length}`;
            captureOverlayBtn.classList.add('opacity-40', 'pointer-events-none');
            if (cancelBtn) {
                cancelBtn.textContent = 'Done';
                cancelBtn.classList.remove('border-zinc-300','text-zinc-700','hover:bg-zinc-100','dark:border-zinc-700','dark:text-zinc-200','dark:hover:bg-zinc-800');
                cancelBtn.classList.add('bg-emerald-600','text-white','hover:bg-emerald-700','border-emerald-600');
            }
            refreshAreaListUI();
            return;
        }
        const item = areaQueue[currentIndex];
        if (areaNameDisplay) areaNameDisplay.textContent = item.dataset.areaPart;
        if (areaCounter) areaCounter.textContent = `${currentIndex + 1}/${areaQueue.length}`;
        if (commentInput) commentInput.placeholder = `Add comment for ${item.dataset.areaPart}...`;
        captureOverlayBtn.classList.remove('opacity-40', 'pointer-events-none');
        refreshAreaListUI();
        item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    };

    const openModal = async (payload) => {
        if (!bindElements()) return;
        if (isOpeningModal) return;
        isOpeningModal = true;
        usingFallback = false;

        setError('');
        preview.classList.add('hidden');
        video.classList.remove('hidden');
        captureOverlayBtn.classList.remove('hidden', 'opacity-40', 'pointer-events-none');
        confirmBtn.classList.add('hidden');
        discardBtn.classList.add('hidden');
        if (commentInput) commentInput.value = '';
        if (cancelBtn) {
            cancelBtn.textContent = 'Cancel';
            cancelBtn.classList.remove('bg-emerald-600','text-white','hover:bg-emerald-700','border-emerald-600');
            cancelBtn.classList.add('border-zinc-300','text-zinc-700','hover:bg-zinc-100','dark:border-zinc-700','dark:text-zinc-200','dark:hover:bg-zinc-800');
        }

        // Hide fallback placeholder on fresh open
        const ph = document.getElementById('proofFallbackPlaceholder');
        if (ph) ph.classList.add('hidden');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        const freq = (payload?.frequency ?? DEFAULT_PROOF_PAYLOAD.frequency ?? '').toLowerCase();
        const shiftToggleGroup = modal.querySelector('[data-shift-group]');
        if (shiftToggleGroup) shiftToggleGroup.classList.toggle('hidden', freq !== 'daily');

        const normalized = activeShift.toUpperCase();
        document.querySelectorAll('.js-shift-toggle').forEach((btn) => {
            const isActive = btn.getAttribute('data-shift') === normalized;
            btn.classList.remove(...SHIFT_ACTIVE_CLASSES, ...SHIFT_INACTIVE_CLASSES);
            btn.classList.add(...(isActive ? SHIFT_ACTIVE_CLASSES : SHIFT_INACTIVE_CLASSES));
            btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });

        await startCamera(); // detects HTTP and uses fallback automatically

        isOpeningModal = false;
        capturedMap = {};
        currentIndex = 0;
        areaQueue = buildQueue();
        areaQueue.forEach(item => {
            const hasAm = item.dataset.hasAm === '1';
            const hasPm = item.dataset.hasPm === '1';
            const alreadyDone = normalized === 'PM' ? hasPm : hasAm;
            if (alreadyDone) capturedMap[item.dataset.partId] = true;
        });
        goToIndex(0);
    };

    const closeModal = () => {
        stopCamera();
        usingFallback = false;
        const ph = document.getElementById('proofFallbackPlaceholder');
        if (ph) ph.classList.add('hidden');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    // ─── Capture (called by shutter button) ──────────────────────────────────
    const handleCapture = () => {
        if (usingFallback) {
            // Open native camera / file picker
            ensureFallbackInput().click();
            return;
        }
        // Live camera path
        areaQueue = buildQueue();
        if (currentIndex >= areaQueue.length) return;
        if (!video.videoWidth || !video.videoHeight) { setError('Camera not ready.'); return; }
        canvas.width = 720; canvas.height = 720;
        const ctx = canvas.getContext('2d');
        const ss = Math.min(video.videoWidth, video.videoHeight);
        const sx = Math.floor((video.videoWidth  - ss) / 2);
        const sy = Math.floor((video.videoHeight - ss) / 2);
        ctx.drawImage(video, sx, sy, ss, ss, 0, 0, 720, 720);
        handleCaptureFromCanvas(ctx);
    };

    // ─── Shared: overlay + Livewire save ─────────────────────────────────────
    const handleCaptureFromCanvas = async (ctx) => {
        areaQueue = buildQueue();
        if (currentIndex >= areaQueue.length) return;
        const item = areaQueue[currentIndex];

        const freq  = item.dataset.frequency;
        const shift = freq === 'daily' ? activeShift : (freq === 'nightly' ? 'PM' : 'AM');

        const now = new Date();
        const currentTime = `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;
        const datePart = (item.dataset.dateLabel || '').split(' | ')[0];
        const dateWithTime = datePart ? `${datePart} | ${currentTime}` : currentTime;

        const overlayPayload = {
            dateLabel:   dateWithTime,
            shift,
            frequency:   item.dataset.frequency,
            capturedBy:  item.dataset.capturedBy,
            areaPart:    item.dataset.areaPart,
            location:    item.dataset.location,
        };

        // ctx may be null if coming from FileReader — re-get it
        if (!ctx) ctx = canvas.getContext('2d');
        const comment = commentInput?.value?.trim() ?? '';
        drawMetadataOverlay(ctx, 720, 720, overlayPayload, comment);

        const imageData = canvas.toDataURL('image/jpeg', 0.9);

        // Show preview before saving
        preview.src = imageData;
        preview.classList.remove('hidden');
        if (!usingFallback) video.classList.add('hidden');
        const ph = document.getElementById('proofFallbackPlaceholder');
        if (ph) ph.classList.add('hidden');

        const component = getChecklistComponent();
        if (!component) { setError('Component not found.'); return; }

        try {
            await component.call('setPendingProof',
                Number(item.dataset.partId),
                String(item.dataset.dayKey),
                shift
            );
            await component.call(
                'confirmToggleWithProof',
                Number(item.dataset.partId),
                String(item.dataset.dayKey),
                shift,
                imageData,
                comment
            );

            capturedMap[item.dataset.partId] = true;

            if (freq === 'daily') {
                if (activeShift === 'PM') item.dataset.hasPm = '1';
                else item.dataset.hasAm = '1';
            } else {
                item.dataset.hasAm = '1';
            }

            if (commentInput) commentInput.value = '';

            requestAnimationFrame(() => {
                document.querySelectorAll('.js-shift-toggle').forEach((btn) => {
                    const isActive = btn.getAttribute('data-shift') === activeShift;
                    btn.classList.remove(...SHIFT_ACTIVE_CLASSES, ...SHIFT_INACTIVE_CLASSES);
                    btn.classList.add(...(isActive ? SHIFT_ACTIVE_CLASSES : SHIFT_INACTIVE_CLASSES));
                    btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });
                const currentShiftGroup = modal?.querySelector('[data-shift-group]');
                if (currentShiftGroup) currentShiftGroup.classList.toggle('hidden', (item.dataset.frequency || '').toLowerCase() !== 'daily');

                // Brief preview then advance
                setTimeout(() => {
                    preview.classList.add('hidden');
                    if (usingFallback) {
                        const ph2 = document.getElementById('proofFallbackPlaceholder');
                        if (ph2) ph2.classList.remove('hidden');
                    } else {
                        video.classList.remove('hidden');
                    }
                    goToIndex(currentIndex + 1);
                    refreshAreaListUI();
                }, 800); // show preview for 800ms then move on
            });
        } catch (e) {
            setError('Failed to save. Try again.');
        }
    };

    const drawMetadataOverlay = (ctx, width, height, payload, comment = '') => {
        const dateText       = payload?.dateLabel   ? String(payload.dateLabel)   : '';
        const shiftText      = payload?.shift       ? String(payload.shift)       : '';
        const frequencyText  = payload?.frequency   ? String(payload.frequency)   : '';
        const capturedByText = payload?.capturedBy  ? String(payload.capturedBy)  : '';
        const areaPartText   = payload?.areaPart    ? String(payload.areaPart)    : '';
        const locationText   = payload?.location    ? String(payload.location)    : '';
        const isDaily = frequencyText.toLowerCase() === 'daily';
        const dateTimeValue = isDaily ? `${dateText}  |  ${shiftText}` : dateText;
        const rows = [
            { label: 'Date/Time',   value: dateTimeValue },
            { label: 'Frequency',   value: frequencyText },
            { label: 'Captured by', value: capturedByText },
            { label: 'Area Part',   value: areaPartText },
            { label: 'Location',    value: locationText },
        ];
        const paddingX      = Math.max(12, Math.round(width * 0.02));
        const paddingY      = 10;
        const fontSize      = Math.max(11, Math.round(width * 0.017));
        const lineHeight    = Math.max(15, Math.round(fontSize * 1.35));
        const labelColWidth = Math.max(84, Math.round(width * 0.19));
        const boxLeft       = 10;
        const boxWidth      = width - 20;
        const valueX        = boxLeft + paddingX + labelColWidth;
        const valueMaxWidth = boxWidth - (paddingX * 2) - labelColWidth;
        const wrapText = (text, maxWidth) => {
            const words = String(text || '').split(/\s+/).filter(Boolean);
            if (!words.length) return [''];
            const lines = []; let current = '';
            for (const word of words) {
                const test = current ? `${current} ${word}` : word;
                if (ctx.measureText(test).width <= maxWidth || !current) current = test;
                else { lines.push(current); current = word; }
            }
            if (current) lines.push(current);
            return lines;
        };
        ctx.textBaseline = 'top';
        ctx.font = `600 ${fontSize}px Arial, sans-serif`;
        const wrappedRows  = rows.map(r => ({ label: r.label, lines: wrapText(r.value, valueMaxWidth) }));
        const contentLines = wrappedRows.reduce((s, r) => s + Math.max(1, r.lines.length), 0);
        const rowGap       = 2;
        const boxHeight    = paddingY * 2 + (contentLines * lineHeight) + ((rows.length - 1) * rowGap);
        const boxTop       = height - boxHeight - 10;
        ctx.fillStyle = 'rgba(0,0,0,0.58)';
        ctx.fillRect(boxLeft, boxTop, boxWidth, boxHeight);
        let y = boxTop + paddingY;
        wrappedRows.forEach(row => {
            ctx.fillStyle = '#D4D4D8';
            ctx.font = `700 ${fontSize}px Arial, sans-serif`;
            ctx.fillText(`${row.label}:`, boxLeft + paddingX, y);
            ctx.fillStyle = '#FFFFFF';
            ctx.font = `600 ${fontSize}px Arial, sans-serif`;
            row.lines.forEach((line, i) => ctx.fillText(line, valueX, y + (i * lineHeight)));
            y += (Math.max(1, row.lines.length) * lineHeight) + rowGap;
        });
        if (comment) {
            const commentPaddingX   = Math.max(12, Math.round(width * 0.02));
            const commentFontSize   = Math.max(10, Math.round(width * 0.016));
            const commentLineHeight = Math.max(14, Math.round(commentFontSize * 1.35));
            const commentMaxWidth   = Math.round((width / 2) - (commentPaddingX * 3));
            ctx.font = `600 ${commentFontSize}px Arial, sans-serif`;
            const commentWords = comment.split(/\s+/).filter(Boolean);
            const commentLines = [];
            let cur = '';
            for (const word of commentWords) {
                const test = cur ? `${cur} ${word}` : word;
                if (ctx.measureText(test).width <= commentMaxWidth || !cur) cur = test;
                else { commentLines.push(cur); cur = word; }
            }
            if (cur) commentLines.push(cur);
            const commentX = boxLeft + Math.floor(boxWidth * 0.62) + commentPaddingX;
            let commentY   = boxTop + paddingY;
            ctx.fillStyle = '#D4D4D8';
            ctx.font = `700 ${commentFontSize}px Arial, sans-serif`;
            ctx.fillText('Comment:', commentX, commentY);
            commentY += commentLineHeight;
            ctx.fillStyle = '#FFFFFF';
            ctx.font = `600 ${commentFontSize}px Arial, sans-serif`;
            commentLines.forEach((line) => {
                ctx.fillText(line, commentX, commentY);
                commentY += commentLineHeight;
            });
        }
    };

    // ─── Event bindings ───────────────────────────────────────────────────────
    document.getElementById('proofCaptureOverlayBtn').addEventListener('click', handleCapture);
    document.getElementById('proofCancelBtn').addEventListener('click', closeModal);

    document.addEventListener('click', (e) => {
        const shiftBtn     = e.target.closest('.js-shift-toggle');
        const dailyOpenBtn = e.target.closest('#openDailyCameraBtn');
        if (shiftBtn)      setActiveShift(shiftBtn.getAttribute('data-shift'));
        if (dailyOpenBtn) {
            const payload = {
                frequency:   dailyOpenBtn.dataset.frequency  || DEFAULT_PROOF_PAYLOAD.frequency,
                dayKey:      dailyOpenBtn.dataset.dayKey     || DEFAULT_PROOF_PAYLOAD.dayKey,
                dateLabel:   dailyOpenBtn.dataset.dateLabel  || DEFAULT_PROOF_PAYLOAD.dateLabel,
                location:    dailyOpenBtn.dataset.location   || DEFAULT_PROOF_PAYLOAD.location,
                capturedBy:  dailyOpenBtn.dataset.capturedBy || DEFAULT_PROOF_PAYLOAD.capturedBy,
            };
            openModal(payload);
        }
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('open-proof-camera', (event) => openModal(event));
    });
    window.addEventListener('open-proof-camera', (e) => openModal(e?.detail ?? e));
})();
</script>
@endonce