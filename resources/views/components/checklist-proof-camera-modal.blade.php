<div id="proofCaptureModal" wire:ignore class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-3">
    <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl dark:bg-zinc-900">
        <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-700">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Capture Proof Photo') }}</h3>
        </div>
        <div class="space-y-3 p-4">
            <div class="relative aspect-square w-full overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                <video id="proofVideo" class="h-full w-full rounded-lg bg-black object-cover"></video>
                <img id="proofPreview" class="hidden h-full w-full rounded-lg bg-black object-cover" alt="{{ __('Proof preview') }}">
            </div>
            <div class="flex justify-center">
                <button type="button" id="proofCaptureOverlayBtn" class="inline-flex aspect-square items-center justify-center rounded-full border-2 border-zinc-300 bg-zinc-500 p-0 leading-none text-white shadow-[0_6px_14px_rgba(0,0,0,0.35)] transition hover:scale-105 hover:bg-zinc-400" style="width:56px;height:56px;min-width:56px;min-height:56px;max-width:56px;max-height:56px;border-radius:9999px;" aria-label="{{ __('Capture photo') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <canvas id="proofCanvas" class="hidden"></canvas>
            <p id="proofError" class="hidden rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700"></p>
            <div class="flex flex-wrap items-center justify-between gap-2">
                <input
                    type="text"
                    id="proofCommentInput"
                    placeholder="{{ __('Add comment...') }}"
                    class="hidden min-w-0 flex-1 rounded-md border border-zinc-300 px-3 py-2 text-sm text-zinc-700 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                />
                <div class="ms-auto flex items-center gap-2">
                <button type="button" id="proofDiscardBtn" class="hidden rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">X</button>
                <button type="button" id="proofConfirmBtn" class="hidden rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">{{ __('Confirm') }}</button>
                <button type="button" id="proofCancelBtn" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">{{ __('Cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@once
    <script>
        (() => {
            let stream = null;
            let capturedData = null;
            let pendingPayload = null;
            let lastValidPayload = null;
            let hasCapture = false;
            let listenersRegistered = false;
            let isOpeningCamera = false;
            let lastOpenSignature = '';
            let lastOpenAt = 0;

            let modal = null;
            let video = null;
            let preview = null;
            let canvas = null;
            let errorBox = null;
            let commentInput = null;
            let captureOverlayBtn = null;
            let discardBtn = null;
            let confirmBtn = null;
            let cancelBtn = null;

            const bindElements = () => {
                modal = document.getElementById('proofCaptureModal');
                video = document.getElementById('proofVideo');
                preview = document.getElementById('proofPreview');
                canvas = document.getElementById('proofCanvas');
                errorBox = document.getElementById('proofError');
                commentInput = document.getElementById('proofCommentInput');
                captureOverlayBtn = document.getElementById('proofCaptureOverlayBtn');
                discardBtn = document.getElementById('proofDiscardBtn');
                confirmBtn = document.getElementById('proofConfirmBtn');
                cancelBtn = document.getElementById('proofCancelBtn');

                return !!(modal && video && preview && canvas && errorBox && commentInput && captureOverlayBtn && discardBtn && confirmBtn && cancelBtn);
            };

            const getChecklistComponent = () => {
                if (!modal || !modal.isConnected) {
                    if (!bindElements()) {
                        return null;
                    }
                }

                const root = modal.closest('[wire\\:id]');
                if (!root || !window.Livewire) {
                    return null;
                }

                const componentId = root.getAttribute('wire:id');
                if (!componentId) {
                    return null;
                }

                return window.Livewire.find(componentId);
            };

            const normalizePayload = (rawEvent) => {
                let payload = rawEvent;

                if (Array.isArray(payload)) {
                    if (payload.length >= 3 && typeof payload[0] !== 'object') {
                        return {
                            partId: payload[0] ?? '',
                            dayKey: payload[1] ?? '',
                            shift: payload[2] ?? '',
                            frequency: payload[3] ?? '',
                            dateLabel: payload[4] ?? '',
                            areaPart: payload[5] ?? '',
                            location: payload[6] ?? '',
                            capturedBy: payload[7] ?? '',
                        };
                    }
                    payload = payload[0] ?? null;
                }

                if (payload && typeof payload === 'object' && 'detail' in payload) {
                    payload = payload.detail;
                }

                if (Array.isArray(payload)) {
                    payload = payload[0] ?? null;
                }

                if (!payload || typeof payload !== 'object') {
                    return null;
                }

                if (!('partId' in payload) && ('0' in payload || '1' in payload || '2' in payload)) {
                    return {
                        partId: payload[0] ?? '',
                        dayKey: payload[1] ?? '',
                        shift: payload[2] ?? '',
                        frequency: payload[3] ?? '',
                        dateLabel: payload[4] ?? '',
                        areaPart: payload[5] ?? '',
                        location: payload[6] ?? '',
                        capturedBy: payload[7] ?? '',
                    };
                }

                return payload;
            };

            const drawMetadataOverlay = (ctx, width, height, payload) => {
                const dateText = payload?.dateLabel ? String(payload.dateLabel) : '';
                const shiftText = payload?.shift ? String(payload.shift) : '';
                const frequencyText = payload?.frequency ? String(payload.frequency) : '';
                const capturedByText = payload?.capturedBy ? String(payload.capturedBy) : '';
                const areaPartText = payload?.areaPart ? String(payload.areaPart) : '';
                const locationText = payload?.location ? String(payload.location) : '';

                const isDaily = frequencyText.toLowerCase() === 'daily';
                const rows = [
                    { label: 'Date', value: isDaily ? `${dateText}  |  ${shiftText}` : dateText },
                    { label: 'Frequency', value: frequencyText },
                    { label: 'Captured by', value: capturedByText },
                    { label: 'Area Part', value: areaPartText },
                    { label: 'Location', value: locationText },
                ];

                const paddingX = Math.max(12, Math.round(width * 0.02));
                const paddingY = 10;
                const fontSize = Math.max(11, Math.round(width * 0.017));
                const lineHeight = Math.max(15, Math.round(fontSize * 1.35));
                const labelColWidth = Math.max(84, Math.round(width * 0.19));
                const boxLeft = 10;
                const boxWidth = width - 20;
                const valueX = boxLeft + paddingX + labelColWidth;
                const valueMaxWidth = boxWidth - (paddingX * 2) - labelColWidth;

                const wrapText = (text, maxWidth) => {
                    const words = String(text || '').split(/\s+/).filter(Boolean);
                    if (words.length === 0) return [''];
                    const lines = [];
                    let current = '';
                    for (const word of words) {
                        const test = current ? `${current} ${word}` : word;
                        if (ctx.measureText(test).width <= maxWidth || current === '') {
                            current = test;
                        } else {
                            lines.push(current);
                            current = word;
                        }
                    }
                    if (current) lines.push(current);
                    return lines;
                };

                ctx.textBaseline = 'top';
                ctx.font = `600 ${fontSize}px Arial, sans-serif`;
                const wrappedRows = rows.map((row) => ({
                    label: row.label,
                    lines: wrapText(row.value, valueMaxWidth),
                }));
                const contentLines = wrappedRows.reduce((sum, row) => sum + Math.max(1, row.lines.length), 0);
                const rowGap = 2;
                const boxHeight = paddingY * 2 + (contentLines * lineHeight) + ((rows.length - 1) * rowGap);
                const boxTop = height - boxHeight - 10;

                ctx.fillStyle = 'rgba(0,0,0,0.58)';
                ctx.fillRect(boxLeft, boxTop, boxWidth, boxHeight);

                let y = boxTop + paddingY;
                wrappedRows.forEach((row) => {
                    ctx.fillStyle = '#D4D4D8';
                    ctx.font = `700 ${fontSize}px Arial, sans-serif`;
                    ctx.fillText(`${row.label}:`, boxLeft + paddingX, y);

                    ctx.fillStyle = '#FFFFFF';
                    ctx.font = `600 ${fontSize}px Arial, sans-serif`;
                    row.lines.forEach((line, idx) => {
                        ctx.fillText(line, valueX, y + (idx * lineHeight));
                    });

                    y += (Math.max(1, row.lines.length) * lineHeight) + rowGap;
                });
            };

            const markChecklistChecked = (partId, dayKey, shift) => {
                const escapedDayKey = String(dayKey).replace(/"/g, '\\"');
                const escapedShift = String(shift).replace(/"/g, '\\"');
                const candidates = [
                    `input[wire\\:key="slot-day-${partId}-${escapedDayKey}-${escapedShift}"]`,
                    `input[wire\\:key="slot-week-${partId}-${escapedDayKey}-${escapedShift}"]`,
                ];

                for (const selector of candidates) {
                    const input = document.querySelector(selector);
                    if (input) {
                        input.checked = true;
                        input.setAttribute('checked', 'checked');
                        break;
                    }
                }
            };

            const setError = (message) => {
                if (!errorBox) {
                    return;
                }

                if (!message) {
                    errorBox.textContent = '';
                    errorBox.classList.add('hidden');
                    return;
                }

                errorBox.textContent = message;
                errorBox.classList.remove('hidden');
            };


            const stopCamera = () => {
                if (stream) {
                    stream.getTracks().forEach((track) => track.stop());
                    stream = null;
                }
                if (video) {
                    video.srcObject = null;
                }
            };

            const setCaptureIconVisible = (visible) => {
                if (visible) {
                    captureOverlayBtn.classList.remove('hidden');
                    captureOverlayBtn.style.display = 'inline-flex';
                } else {
                    captureOverlayBtn.classList.add('hidden');
                    captureOverlayBtn.style.display = 'none';
                }
            };

            const resetPreview = () => {
                capturedData = null;
                hasCapture = false;
                preview.classList.add('hidden');
                video.classList.remove('hidden');
                preview.src = '';
                setCaptureIconVisible(true);
                commentInput.value = '';
                commentInput.classList.add('hidden');
                discardBtn.classList.add('hidden');
                confirmBtn.classList.add('hidden');
                cancelBtn.classList.remove('hidden');
                setError('');
            };

            const closeModal = (cancel = false) => {
                if (!modal) {
                    return;
                }

                modal.classList.add('hidden');
                modal.classList.remove('flex');
                stopCamera();
                resetPreview();

                if (cancel) {
                    const component = getChecklistComponent();
                    if (component) {
                        component.call('cancelProofCapture');
                    }
                }

                pendingPayload = null;
            };

            const openModal = async (payload) => {
                if (!bindElements()) {
                    return;
                }

                const normalized = normalizePayload(payload);
                if (!normalized) {
                    return;
                }

                pendingPayload = {
                    partId: normalized.partId ?? '',
                    dayKey: normalized.dayKey ?? '',
                    shift: normalized.shift ?? '',
                    frequency: normalized.frequency ?? '',
                    dateLabel: normalized.dateLabel ?? '',
                    areaPart: normalized.areaPart ?? '',
                    capturedBy: normalized.capturedBy ?? '',
                };

                const hasCorePayload = pendingPayload.partId !== '' && pendingPayload.dayKey !== '' && pendingPayload.shift !== '';
                if (hasCorePayload) {
                    lastValidPayload = { ...pendingPayload };
                } else if (lastValidPayload) {
                    pendingPayload = { ...lastValidPayload };
                }

                const needsServerMetadata = (
                    !String(pendingPayload?.location ?? '').trim()
                    || !String(pendingPayload?.areaPart ?? '').trim()
                    || !String(pendingPayload?.dateLabel ?? '').trim()
                    || !String(pendingPayload?.frequency ?? '').trim()
                    || !String(pendingPayload?.capturedBy ?? '').trim()
                );

                if (needsServerMetadata) {
                    const component = getChecklistComponent();
                    if (component && pendingPayload?.partId && pendingPayload?.dayKey && pendingPayload?.shift) {
                        try {
                            const serverMeta = await component.call(
                                'getProofMetadata',
                                Number(pendingPayload.partId),
                                String(pendingPayload.dayKey),
                                String(pendingPayload.shift)
                            );
                            if (serverMeta && typeof serverMeta === 'object') {
                                pendingPayload = {
                                    ...pendingPayload,
                                    partId: serverMeta.partId ?? pendingPayload.partId,
                                    dayKey: serverMeta.dayKey ?? pendingPayload.dayKey,
                                    shift: serverMeta.shift ?? pendingPayload.shift,
                                    frequency: serverMeta.frequency ?? pendingPayload.frequency,
                                    dateLabel: serverMeta.dateLabel ?? pendingPayload.dateLabel,
                                    areaPart: serverMeta.areaPart ?? pendingPayload.areaPart,
                                    location: serverMeta.location ?? pendingPayload.location,
                                    capturedBy: serverMeta.capturedBy ?? pendingPayload.capturedBy,
                                };
                                lastValidPayload = { ...pendingPayload };
                            }
                        } catch (_) {
                            // Keep current payload fallback.
                        }
                    }
                }

                const signature = `${pendingPayload.partId}|${pendingPayload.dayKey}|${pendingPayload.shift}|${pendingPayload.frequency}|${pendingPayload.dateLabel}`;
                const now = Date.now();
                if (isOpeningCamera || (signature === lastOpenSignature && (now - lastOpenAt) < 600)) {
                    return;
                }
                lastOpenSignature = signature;
                lastOpenAt = now;
                isOpeningCamera = true;

                setError('');
                resetPreview();
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                try {
                    // Stop old tracks first in case previous open attempt left camera busy.
                    stopCamera();

                    const vw = window.innerWidth || 360;
                    const vh = window.innerHeight || 640;
                    const isLandscape = vw > vh;
                    const isMobile = vw < 768;

                    const idealWidth = isMobile ? (isLandscape ? 1280 : 720) : 1280;
                    const idealHeight = isMobile ? (isLandscape ? 720 : 1280) : 960;

                    try {
                        // Primary: back camera + responsive ideal dimensions.
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: { ideal: 'environment' },
                                width: { ideal: 720 },
                                height: { ideal: 720 },
                                aspectRatio: { ideal: 1 }
                            },
                            audio: false
                        });
                    } catch (primaryError) {
                        try {
                            // Fallback: drop facingMode but keep responsive dimensions.
                            stream = await navigator.mediaDevices.getUserMedia({
                                video: {
                                    width: { ideal: 720 },
                                    height: { ideal: 720 },
                                    aspectRatio: { ideal: 1 }
                                },
                                audio: false
                            });
                        } catch (secondaryError) {
                            // Final fallback for older browsers/devices.
                            stream = await navigator.mediaDevices.getUserMedia({
                                video: true,
                                audio: false
                            });
                        }
                    }

                    video.srcObject = stream;
                    await video.play();
                    setError('');
                } catch (error) {
                    setError('Camera access failed. Please allow camera permission and try again.');
                } finally {
                    isOpeningCamera = false;
                }
            };

            const handleCaptureClick = () => {
                if (!video.videoWidth || !video.videoHeight) {
                    setError('Camera is not ready yet. Please try again.');
                    return;
                }

                canvas.width = 720;
                canvas.height = 720;
                const ctx = canvas.getContext('2d');
                const sourceWidth = video.videoWidth;
                const sourceHeight = video.videoHeight;
                const sourceSize = Math.min(sourceWidth, sourceHeight);
                const sourceX = Math.floor((sourceWidth - sourceSize) / 2);
                const sourceY = Math.floor((sourceHeight - sourceSize) / 2);
                ctx.drawImage(video, sourceX, sourceY, sourceSize, sourceSize, 0, 0, 720, 720);
                drawMetadataOverlay(ctx, canvas.width, canvas.height, pendingPayload);
                capturedData = canvas.toDataURL('image/jpeg', 0.9);
                hasCapture = true;

                preview.src = capturedData;
                video.classList.add('hidden');
                preview.classList.remove('hidden');
                setCaptureIconVisible(false);
                commentInput.classList.remove('hidden');
                discardBtn.classList.remove('hidden');
                confirmBtn.classList.remove('hidden');
                cancelBtn.classList.add('hidden');
                stopCamera();
            };

            const handleDiscardClick = async () => {
                const effectivePayload = pendingPayload || lastValidPayload;
                if (!effectivePayload) {
                    return;
                }
                await openModal(effectivePayload);
            };

            const handleConfirmClick = async () => {
                const effectivePayload = pendingPayload || lastValidPayload;
                const effectiveImageData = capturedData;

                if (!hasCapture || !effectiveImageData || !effectiveImageData.startsWith('data:image/') || !effectivePayload) {
                    setError('Please capture a photo first.');
                    return;
                }

                const component = getChecklistComponent();
                if (!component) {
                    setError('Checklist component was not found. Please refresh and try again.');
                    return;
                }

                try {
                    await component.call(
                        'confirmToggleWithProof',
                        Number(effectivePayload.partId),
                        String(effectivePayload.dayKey),
                        String(effectivePayload.shift),
                        effectiveImageData,
                        String(commentInput.value ?? '')
                    );

                    markChecklistChecked(
                        Number(effectivePayload.partId),
                        String(effectivePayload.dayKey),
                        String(effectivePayload.shift)
                    );

                    closeModal(false);
                } catch (error) {
                    setError('Unable to confirm checklist. Please try again.');
                }
            };

            const handleCancelClick = () => closeModal(true);

            const bindHandlers = () => {
                if (!bindElements()) {
                    return false;
                }

                if (!captureOverlayBtn.dataset.bound) {
                    captureOverlayBtn.addEventListener('click', handleCaptureClick);
                    captureOverlayBtn.dataset.bound = '1';
                }
                if (!discardBtn.dataset.bound) {
                    discardBtn.addEventListener('click', handleDiscardClick);
                    discardBtn.dataset.bound = '1';
                }
                if (!confirmBtn.dataset.bound) {
                    confirmBtn.addEventListener('click', handleConfirmClick);
                    confirmBtn.dataset.bound = '1';
                }
                if (!cancelBtn.dataset.bound) {
                    cancelBtn.addEventListener('click', handleCancelClick);
                    cancelBtn.dataset.bound = '1';
                }

                return true;
            };

            bindHandlers();

            const registerLivewireListeners = () => {
                if (listenersRegistered || !window.Livewire) {
                    return;
                }
                listenersRegistered = true;

                Livewire.on('open-proof-camera', (...args) => {
                    if (!bindHandlers()) {
                        return;
                    }
                    const rawEvent = args.length <= 1 ? args[0] : args;
                    openModal(rawEvent);
                });

                Livewire.on('proof-capture-error', (...args) => {
                    bindHandlers();
                    const rawEvent = args.length <= 1 ? args[0] : args;
                    const payload = normalizePayload(rawEvent);
                    const message = payload?.message ?? 'Unable to save proof photo.';
                    setError(message);
                });
            };

            window.addEventListener('open-proof-camera', (event) => {
                if (!bindHandlers()) {
                    return;
                }
                openModal(event?.detail ?? event);
            });

            window.addEventListener('proof-capture-error', (event) => {
                bindHandlers();
                const payload = normalizePayload(event?.detail ?? event);
                const message = payload?.message ?? 'Unable to save proof photo.';
                setError(message);
            });

            registerLivewireListeners();
            document.addEventListener('livewire:init', registerLivewireListeners);
        })();
    </script>
@endonce
