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

    {{-- Eliminate 300 ms tap delay on all buttons inside this modal --}}
    @once
    <style>
        #proofCaptureModal button,
        #proofCaptureModal [role="button"] {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
    @endonce

    <div id="proofCaptureModal" class="fixed inset-0 z-50 hidden bg-black/70 p-2 overflow-y-auto" wire:ignore.self>
        <div class="w-full max-w-sm mx-auto bg-white shadow-2xl dark:bg-zinc-900 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between border-b border-zinc-200 px-3 py-2 dark:border-zinc-700">
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Capture Proof Photo') }}</h3>
                <div class="flex items-center gap-2">
                    <span id="proofSavingBadge" class="hidden items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700 dark:bg-amber-900/40 dark:text-amber-400">
                        <span class="inline-block h-1.5 w-1.5 animate-pulse rounded-full bg-amber-500"></span>
                        <span id="proofSavingBadgeText">Saving…</span>
                    </span>
                    <span id="proofAreaCounter" class="text-xs text-zinc-500 dark:text-zinc-400"></span>
                    <span id="proofOfflineBadge" class="hidden items-center gap-1 rounded-full bg-orange-100 px-2 py-0.5 text-[10px] font-semibold text-orange-700 dark:bg-orange-900/40 dark:text-orange-400">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                        <span id="proofOfflineBadgeText">Offline</span>
                    </span>
                </div>
            </div>
            <div class="p-3 max-h-[calc(100vh-20px)] sm:max-h-[calc(100vh-24px)] overflow-y-auto">
                <div class="flex flex-col gap-2">

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
                    <div id="proofStepIndicator" class="flex gap-2 overflow-x-auto pb-1 justify-start">
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
                                class="js-proof-area-item flex flex-col items-center gap-1 min-h-[3rem]"
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
                                <span class="text-[9px] text-center text-zinc-500 dark:text-zinc-400 leading-tight w-full min-h-[1.8em]">{{ $part['display_name'] }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-zinc-400 py-2">{{ __('No area parts available') }}</p>
                        @endforelse
                    </div>

                    {{-- Camera --}}
                    <div class="w-full space-y-2">
                        <div class="relative aspect-square w-full overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                            <video id="proofVideo" class="h-full w-full rounded-lg bg-black object-cover" autoplay playsinline muted></video>
                            <img id="proofPreview" class="hidden h-full w-full rounded-lg bg-black object-cover" alt="{{ __('Proof preview') }}">
                        </div>

                        <div class="flex items-center justify-center gap-4">
                            {{-- Skip: Patient Present --}}
                            <button type="button" id="proofSkipPatientBtn"
                                class="flex flex-col items-center gap-1 rounded-xl border border-amber-300 bg-amber-50 px-2 py-2 text-[10px] font-semibold text-amber-700 transition hover:bg-amber-100 dark:border-amber-700 dark:bg-amber-900/30 dark:text-amber-400"
                                style="min-width:58px;"
                                aria-label="{{ __('Skip — patient present') }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Patient
                            </button>

                            {{-- Shutter --}}
                            <button type="button" id="proofCaptureOverlayBtn"
                                class="inline-flex aspect-[4/3] items-center justify-center rounded-full border-2 border-zinc-300 bg-zinc-500 p-0 leading-none text-white shadow-[0_6px_14px_rgba(0,0,0,0.35)] transition hover:scale-105 hover:bg-zinc-400"
                                style="width:56px;height:56px;min-width:56px;min-height:56px;max-width:56px;max-height:56px;border-radius:9999px;"
                                aria-label="{{ __('Capture photo') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            {{-- Skip: Gloves On --}}
                            <button type="button" id="proofSkipGlovesBtn"
                                class="flex flex-col items-center gap-1 rounded-xl border border-amber-300 bg-amber-50 px-2 py-2 text-[10px] font-semibold text-amber-700 transition hover:bg-amber-100 dark:border-amber-700 dark:bg-amber-900/30 dark:text-amber-400"
                                style="min-width:58px;"
                                aria-label="{{ __('Skip — gloves on') }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
                                </svg>
                                Gloves
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

    // ─── IndexedDB offline queue ──────────────────────────────────────────────
    const IDB_NAME  = 'nlah-checklist';
    const IDB_STORE = 'pending';

    const openChecklistDB = () => new Promise((resolve, reject) => {
        const req = indexedDB.open(IDB_NAME, 1);
        req.onupgradeneeded = (e) => e.target.result.createObjectStore(IDB_STORE, { keyPath: 'id', autoIncrement: true });
        req.onsuccess       = (e) => resolve(e.target.result);
        req.onerror         = (e) => reject(e.target.error);
    });

    const idbSavePending = async (task) => {
        try {
            const db = await openChecklistDB();
            const tx = db.transaction(IDB_STORE, 'readwrite');
            tx.objectStore(IDB_STORE).add({ ...task, savedAt: Date.now() });
            await new Promise((res, rej) => { tx.oncomplete = res; tx.onerror = rej; });
            db.close();
        } catch (e) { console.error('IDB save failed:', e); }
    };

    const idbGetAll = async () => {
        try {
            const db = await openChecklistDB();
            const tx = db.transaction(IDB_STORE, 'readonly');
            const results = await new Promise((res, rej) => {
                const r = tx.objectStore(IDB_STORE).getAll();
                r.onsuccess = () => res(r.result);
                r.onerror   = () => rej(r.error);
            });
            db.close();
            return results;
        } catch (e) { return []; }
    };

    const idbDelete = async (id) => {
        try {
            const db = await openChecklistDB();
            const tx = db.transaction(IDB_STORE, 'readwrite');
            tx.objectStore(IDB_STORE).delete(id);
            await new Promise((res, rej) => { tx.oncomplete = res; tx.onerror = rej; });
            db.close();
        } catch (e) {}
    };

    let offlinePendingCount = 0;

    const updateOfflineBadge = () => {
        const badge = document.getElementById('proofOfflineBadge');
        const text  = document.getElementById('proofOfflineBadgeText');
        if (!badge) return;
        if (offlinePendingCount > 0) {
            if (text) text.textContent = offlinePendingCount > 1 ? `${offlinePendingCount} offline` : 'Offline';
            badge.classList.remove('hidden');
            badge.classList.add('inline-flex');
        } else {
            badge.classList.add('hidden');
            badge.classList.remove('inline-flex');
        }
    };

    // Parse "Apr 10, 2026 | 10:30" → "2026-04-10" using local date parts to avoid UTC offset bug
    const parseDateLabel = (label) => {
        if (!label) return null;
        try {
            const d = new Date(label.split(' | ')[0].trim());
            if (isNaN(d)) return null;
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        } catch (e) { return null; }
    };

    let isFlushing = false;

    const flushFromIDB = async () => {
        if (isFlushing) return;
        isFlushing = true;
        try {
            const pending = await idbGetAll();
            if (!pending.length) { isFlushing = false; return; }
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            let synced = 0;
            for (const task of pending) {
                if (!navigator.onLine) break;
                try {
                    const res = await fetch('/api/maintenance/checklist/sync', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ records: [task] }),
                    });
                    if (res.ok) {
                        await idbDelete(task.id);
                        offlinePendingCount = Math.max(0, offlinePendingCount - 1);
                        updateOfflineBadge();
                        synced++;
                    }
                } catch (e) {
                    // Network error on this record — skip it and try the next one
                    continue;
                }
            }
            if (synced > 0) {
                const component = getChecklistComponent();
                // reloadSlots() re-runs loadExistingSlots() so slotProofs is updated
                // and the proof preview button appears for newly-synced photos.
                if (component) { try { component.call('reloadSlots'); } catch (e) {} }
            }
        } finally {
            isFlushing = false;
        }
    };

    window.addEventListener('online', () => setTimeout(flushFromIDB, 1500)); // brief delay so connection stabilises
    document.addEventListener('visibilitychange', () => { if (!document.hidden && offlinePendingCount > 0) flushFromIDB(); });

    // Periodic retry every 20 s — catches cases where online event misfires
    setInterval(() => { if (offlinePendingCount > 0 && navigator.onLine) flushFromIDB(); }, 20000);

    // Check for leftover offline records on page load
    idbGetAll().then((rows) => {
        offlinePendingCount = rows.length;
        updateOfflineBadge();
        if (rows.length > 0 && navigator.onLine) flushFromIDB();
    });

    // ─── Background save queue ────────────────────────────────────────────────
    let uploadQueue     = [];
    let isSavingQueue   = false;
    let pendingSaveCount = 0;

    const updatePendingIndicator = () => {
        const badge     = document.getElementById('proofSavingBadge');
        const badgeText = document.getElementById('proofSavingBadgeText');
        if (!badge) return;
        if (pendingSaveCount > 0) {
            if (badgeText) badgeText.textContent = pendingSaveCount > 1 ? `Saving ${pendingSaveCount}…` : 'Saving…';
            badge.classList.remove('hidden');
            badge.classList.add('inline-flex');
        } else {
            badge.classList.add('hidden');
            badge.classList.remove('inline-flex');
        }
    };

    const processSaveQueue = async () => {
        if (isSavingQueue) return;
        isSavingQueue = true;
        while (uploadQueue.length > 0) {
            const task = uploadQueue.shift();

            if (!navigator.onLine) {
                // Dead spot — store locally, UI already shows the check optimistically
                await idbSavePending(task);
                offlinePendingCount++;
                pendingSaveCount = Math.max(0, pendingSaveCount - 1);
                updatePendingIndicator();
                updateOfflineBadge();
                continue;
            }

            try {
                const component = getChecklistComponent();
                if (component) {
                    if (task.skipReason) {
                        await component.call('confirmToggleWithSkip', task.partId, task.dayKey, task.shift, task.skipReason);
                    } else {
                        await component.call('confirmToggleWithProof', task.partId, task.dayKey, task.shift, task.imageData, task.comment);
                    }
                }
            } catch (e) {
                // Always fall back to IDB on any Livewire call failure.
                // navigator.onLine can be true even when the network is unreachable
                // (e.g., WiFi with no internet), so checking it here causes silent
                // photo loss.  Saving to IDB is always safe — flushFromIDB will
                // retry when connectivity is genuinely restored.
                await idbSavePending(task);
                offlinePendingCount++;
                updateOfflineBadge();
            }
            pendingSaveCount = Math.max(0, pendingSaveCount - 1);
            updatePendingIndicator();
        }
        isSavingQueue = false;
    };

    const enqueueSave = (task) => {
        pendingSaveCount++;
        uploadQueue.push(task);
        updatePendingIndicator();
        processSaveQueue(); // fire-and-forget
    };

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
        // Prefer the modal's own Livewire ancestor; fall back to any wire:id on
        // the page so reloadSlots() works even if the modal was never opened
        // (e.g., flush triggered by the 'online' event on page load).
        const root = modal?.closest('[wire\\:id]')
                  ?? document.querySelector('[wire\\:id]');
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

    // ─── Shared: overlay + optimistic UI + background save ───────────────────
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

        if (!ctx) ctx = canvas.getContext('2d');
        const comment = commentInput?.value?.trim() ?? '';
        drawMetadataOverlay(ctx, 720, 720, overlayPayload, comment);
        const imageData = canvas.toDataURL('image/jpeg', 0.9);

        // ── 1. Optimistic UI — update immediately, no server round-trip ───────
        capturedMap[item.dataset.partId] = true;
        if (freq === 'daily') {
            if (activeShift === 'PM') item.dataset.hasPm = '1';
            else item.dataset.hasAm = '1';
        } else {
            item.dataset.hasAm = '1';
        }
        if (commentInput) commentInput.value = '';

        // ── 2. Queue the actual save — runs silently in background ────────────
        enqueueSave({
            partId:       Number(item.dataset.partId),
            dayKey:       String(item.dataset.dayKey),
            shift,
            imageData,
            comment,
            periodType:   String(item.dataset.frequency  ?? 'daily'),
            selectedDate: parseDateLabel(item.dataset.dateLabel) ?? new Date().toISOString().split('T')[0],
        });

        // ── 3. Flash preview briefly then advance to next area immediately ────
        preview.src = imageData;
        preview.classList.remove('hidden');
        if (!usingFallback) video.classList.add('hidden');
        const ph = document.getElementById('proofFallbackPlaceholder');
        if (ph) ph.classList.add('hidden');

        requestAnimationFrame(() => {
            document.querySelectorAll('.js-shift-toggle').forEach((btn) => {
                const isActive = btn.getAttribute('data-shift') === activeShift;
                btn.classList.remove(...SHIFT_ACTIVE_CLASSES, ...SHIFT_INACTIVE_CLASSES);
                btn.classList.add(...(isActive ? SHIFT_ACTIVE_CLASSES : SHIFT_INACTIVE_CLASSES));
                btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
            const currentShiftGroup = modal?.querySelector('[data-shift-group]');
            if (currentShiftGroup) {
                currentShiftGroup.classList.toggle('hidden', (item.dataset.frequency || '').toLowerCase() !== 'daily');
            }

            setTimeout(async () => {
                preview.classList.add('hidden');
                if (usingFallback) {
                    const ph2 = document.getElementById('proofFallbackPlaceholder');
                    if (ph2) ph2.classList.remove('hidden');
                } else {
                    await startCamera();
                }
                goToIndex(currentIndex + 1);
                refreshAreaListUI();
            }, 250); // quick flash — user moves on immediately
        });
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

    // ─── Skip handler (patient present / gloves) ──────────────────────────────
    const handleSkip = (skipReason) => {
        areaQueue = buildQueue();
        if (currentIndex >= areaQueue.length) return;
        const item = areaQueue[currentIndex];
        const freq  = item.dataset.frequency;
        const shift = freq === 'daily' ? activeShift : (freq === 'nightly' ? 'PM' : 'AM');
        
        // Mark as captured FIRST so refreshAreaListUI shows checkmark NOW
        capturedMap[item.dataset.partId] = true;
        if (freq === 'daily') {
            if (activeShift === 'PM') item.dataset.hasPm = '1';
            else item.dataset.hasAm = '1';
        } else {
            item.dataset.hasAm = '1';
        }
        
        // Immediately update UI to show checkmark before any delay
        refreshAreaListUI();
        
        // Show visual feedback (colored placeholder)
        if (preview && canvas) {
            const ctx = canvas.getContext('2d');
            canvas.width = 720; canvas.height = 720;
            ctx.fillStyle = skipReason === 'patient_present' ? '#f59e0b' : '#10b981';
            ctx.fillRect(0, 0, 720, 720);
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 48px Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const label = skipReason === 'patient_present' ? '⛔ Patient' : '🧤 Gloves';
            ctx.fillText(label, 360, 360);
            const imageData = canvas.toDataURL('image/jpeg', 0.9);
            preview.src = imageData;
            preview.classList.remove('hidden');
            if (!usingFallback) video.classList.add('hidden');
            const ph = document.getElementById('proofFallbackPlaceholder');
            if (ph) ph.classList.add('hidden');
        }
        
        if (commentInput) commentInput.value = '';
        enqueueSave({ partId: Number(item.dataset.partId), dayKey: String(item.dataset.dayKey), shift, imageData: null, comment: null, skipReason, periodType: String(item.dataset.frequency ?? 'daily'), selectedDate: parseDateLabel(item.dataset.dateLabel) ?? new Date().toISOString().split('T')[0] });
        
        // Check if this is the last item - advance and show done state
        const nextIdx = currentIndex + 1;
        if (nextIdx >= areaQueue.length) {
            // All done - show checkmark briefly then close
            setTimeout(async () => {
                preview.classList.add('hidden');
                if (!usingFallback) await startCamera();
                if (areaNameDisplay) areaNameDisplay.textContent = '✓ All areas captured!';
                if (areaCounter) areaCounter.textContent = `${areaQueue.length}/${areaQueue.length}`;
                captureOverlayBtn.classList.add('opacity-40', 'pointer-events-none');
                if (cancelBtn) {
                    cancelBtn.textContent = 'Done';
                    cancelBtn.classList.remove('border-zinc-300','text-zinc-700','hover:bg-zinc-100','dark:border-zinc-700','dark:text-zinc-200','dark:hover:bg-zinc-800');
                    cancelBtn.classList.add('bg-emerald-600','text-white','hover:bg-emerald-700','border-emerald-600');
                }
            }, 300);
        } else {
            // Not last - advance to next
            setTimeout(async () => {
                preview.classList.add('hidden');
                if (!usingFallback) await startCamera();
                goToIndex(nextIdx);
            }, 300);
        }
    };

    // ─── Element-scoped bindings (safe to re-bind — these elements are replaced on navigate) ──
    document.getElementById('proofCaptureOverlayBtn').addEventListener('click', handleCapture);
    document.getElementById('proofCancelBtn').addEventListener('click', closeModal);
    document.getElementById('proofSkipPatientBtn').addEventListener('click', () => handleSkip('patient_present'));
    document.getElementById('proofSkipGlovesBtn').addEventListener('click', () => handleSkip('gloves'));

    // ─── Global listeners — guard against duplicate registration across wire:navigate ──────────
    // Each wire:navigate to this page re-executes this script. Without a guard, global
    // document/window listeners accumulate and create thousands of closures in memory.
    if (!window.__proofCameraModalBound) {
        window.__proofCameraModalBound = true;

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

        window.addEventListener('open-proof-camera', (e) => openModal(e?.detail ?? e));

        document.addEventListener('livewire:init', () => {
            Livewire.on('open-proof-camera', (event) => openModal(event));
        });

        // Reset flag when user navigates away so the handlers re-attach to the
        // refreshed DOM element references (openModal/handleCapture etc.) on return.
        document.addEventListener('livewire:navigating', () => {
            window.__proofCameraModalBound = false;
        });
    }
})();
</script>
@endonce