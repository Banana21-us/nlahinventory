export default function voiceRound() {
    return {
        recognition: null,
        listening: false,
        supported: false,

        init() {
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SR) return;
            this.supported = true;
            this.recognition = new SR();
            this.recognition.continuous = true;
            this.recognition.interimResults = false;
            this.recognition.lang = 'en-US';
            this.recognition.onresult = (e) => {
                const cmd = e.results[e.results.length - 1][0]
                    .transcript.trim().toLowerCase();
                if (['next', 'skip', 'done', 'back'].includes(cmd)) {
                    navigator.vibrate?.(50);
                    this.$dispatch('voice-command', { command: cmd });
                    document.body.style.outline = '4px solid #22c55e';
                    setTimeout(() => (document.body.style.outline = ''), 400);
                }
            };
            this.recognition.onend = () => {
                if (this.listening) this.recognition.start();
            };
        },

        toggle() {
            this.listening = !this.listening;
            this.listening
                ? this.recognition?.start()
                : this.recognition?.stop();
        },
    };
}
