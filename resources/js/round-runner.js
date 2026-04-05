export default function roundRunner() {
    return {
        // camera
        cameraMode: 'loading', // loading | live | fallback | preview | error
        cameraError: '',
        stream: null,
        // upload
        uploadStatus: '',
        // transition overlay
        showTransitionOverlay: false,
        nextAreaName: '',
        // voice
        recognition: null,
        listening: false,
        voiceSupported: false,

        async init() {
            // Listen for Livewire custom events dispatched from PHP
            this.$wire.on('area-changed', (payload) => {
                const isCR = Array.isArray(payload)
                    ? payload[0]?.isCR
                    : payload?.isCR;
                this.onAreaChanged(!!isCR);
            });
            this.$wire.on('item-advanced', (payload) => {
                const next = Array.isArray(payload)
                    ? payload[0]?.nextArea
                    : payload?.nextArea;
                this.showTransition(next || '');
            });

            const isCR = !!this.$wire.isCR;
            if (!isCR) {
                await this.initCamera();
            } else {
                this.cameraMode = 'fallback';
            }

            if (this.$wire.voiceEnabled) {
                this.startVoice();
            }

            if (typeof window.retryQueue === 'function') {
                window.retryQueue();
            }
        },

        isSecureContext() {
            return window.isSecureContext
                || location.protocol === 'https:'
                || location.hostname === 'localhost'
                || location.hostname === '127.0.0.1';
        },

        async initCamera() {
            this.cameraMode = 'loading';
            this.cameraError = '';

            if (this.stream) {
                this.stream.getTracks().forEach((t) => t.stop());
                this.stream = null;
            }

            if (!this.isSecureContext() || !navigator.mediaDevices?.getUserMedia) {
                this.cameraMode = 'fallback';
                this.ensureFallbackInput();
                return;
            }

            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: { ideal: 'environment' },
                        width: { ideal: 1280 },
                    },
                    audio: false,
                });
                const video = document.getElementById('rr-video');
                if (!video) {
                    this.cameraMode = 'fallback';
                    this.ensureFallbackInput();
                    return;
                }
                video.srcObject = this.stream;
                await new Promise((resolve, reject) => {
                    video.onloadedmetadata = resolve;
                    video.onerror = reject;
                    setTimeout(reject, 8000);
                });
                await video.play().catch(() => {});
                this.cameraMode = 'live';
            } catch (e) {
                if (e?.name === 'NotAllowedError') {
                    this.cameraError = 'Camera permission denied. Please allow camera access.';
                    this.cameraMode = 'error';
                } else {
                    this.cameraMode = 'fallback';
                    this.ensureFallbackInput();
                }
            }
        },

        ensureFallbackInput() {
            if (document.getElementById('rr-fallback-input')) return;
            const fi = document.createElement('input');
            fi.type = 'file';
            fi.accept = 'image/*';
            fi.capture = 'environment';
            fi.id = 'rr-fallback-input';
            fi.style.cssText =
                'position:fixed;opacity:0;pointer-events:none;top:0;left:0;width:1px;height:1px;';
            document.body.appendChild(fi);
            fi.addEventListener('change', (e) => {
                const file = e.target.files?.[0];
                fi.value = '';
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (ev) => {
                    const img = new Image();
                    img.onload = () => {
                        const canvas = document.getElementById('rr-canvas');
                        canvas.width = 1024;
                        canvas.height = Math.round(img.height * (1024 / img.width));
                        canvas
                            .getContext('2d')
                            .drawImage(img, 0, 0, canvas.width, canvas.height);
                        this.handleCaptureFromCanvas();
                    };
                    img.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            });
        },

        async capturePhoto() {
            if (this.$wire.processing) return;

            if (this.cameraMode === 'fallback') {
                document.getElementById('rr-fallback-input')?.click();
                return;
            }

            if (this.cameraMode !== 'live') return;

            const video = document.getElementById('rr-video');
            const canvas = document.getElementById('rr-canvas');
            canvas.width = video.videoWidth || 1024;
            canvas.height = video.videoHeight || 768;
            canvas.getContext('2d').drawImage(video, 0, 0);
            this.handleCaptureFromCanvas();
        },

        handleCaptureFromCanvas() {
            const canvas = document.getElementById('rr-canvas');
            const base64 = canvas.toDataURL('image/jpeg', 0.85);

            const preview = document.getElementById('rr-preview');
            if (preview) {
                preview.src = base64;
            }
            this.cameraMode = 'preview';

            this.uploadAndAdvance(base64);
        },

        async uploadAndAdvance(base64) {
            this.uploadStatus = 'uploading';
            const itemId = this.$wire.get('currentItem')?.id;

            // Optimistically advance the Livewire item (marks completed w/
            // a temporary photo_path that the upload endpoint will replace).
            this.$wire.call('advanceAfterCapture');

            if (window.uploadPhoto) {
                try {
                    await window.uploadPhoto(base64, itemId);
                    this.uploadStatus = 'ok';
                } catch {
                    this.uploadStatus = 'error';
                }
                setTimeout(() => {
                    this.uploadStatus = '';
                }, 2500);
            }
        },

        onAreaChanged(isCR) {
            if (!isCR) {
                this.$nextTick(() => this.initCamera());
            } else {
                if (this.stream) {
                    this.stream.getTracks().forEach((t) => t.stop());
                    this.stream = null;
                }
                this.cameraMode = 'fallback';
            }
        },

        showTransition(nextArea) {
            this.nextAreaName = nextArea || '';
            this.showTransitionOverlay = true;
            setTimeout(() => {
                this.showTransitionOverlay = false;
            }, 1500);
        },

        startVoice() {
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SR) {
                this.voiceSupported = false;
                return;
            }
            this.voiceSupported = true;
            try {
                this.recognition = new SR();
                this.recognition.continuous = true;
                this.recognition.interimResults = false;
                this.recognition.lang = 'en-US';
                this.recognition.onresult = (e) => {
                    const cmd = e.results[e.results.length - 1][0].transcript
                        .trim()
                        .toLowerCase();
                    this.handleVoice(cmd);
                };
                this.recognition.onerror = (e) => {
                    if (e?.error === 'not-allowed' || e?.error === 'service-not-allowed') {
                        this.listening = false;
                    }
                };
                this.recognition.onend = () => {
                    if (this.listening) {
                        try {
                            this.recognition.start();
                        } catch {
                            /* ignore already-started */
                        }
                    }
                };
                this.listening = true;
                this.recognition.start();
            } catch {
                this.listening = false;
            }
        },

        toggleVoice() {
            if (this.listening) {
                this.listening = false;
                this.recognition?.stop();
            } else {
                if (this.recognition) {
                    this.listening = true;
                    try {
                        this.recognition.start();
                    } catch {
                        /* already started */
                    }
                } else {
                    this.startVoice();
                }
            }
        },

        handleVoice(cmd) {
            navigator.vibrate?.(50);
            if (cmd.includes('next') || cmd.includes('done')) {
                if (this.$wire.get('isCR')) {
                    this.$wire.call('completeChecklist');
                } else {
                    this.capturePhoto();
                }
            } else if (cmd.includes('skip')) {
                this.$wire.call('openSkipModal');
            } else if (cmd.includes('back')) {
                this.$wire.call('previousItem');
            }
        },
    };
}
