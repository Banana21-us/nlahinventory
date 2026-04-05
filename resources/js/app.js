import Sortable from 'sortablejs';
import { uploadPhoto, retryQueue } from './photo-upload.js';
import voiceRound from './voice-round.js';
import roundRunner from './round-runner.js';

// Livewire ships Alpine (and auto-starts it). Importing 'alpinejs' here or
// calling Alpine.start() would double-initialize Alpine and break Livewire's
// reactivity / persistence of Alpine state across DOM morphs.
window.Sortable = Sortable;
window.uploadPhoto = uploadPhoto;
window.retryQueue = retryQueue;

document.addEventListener('alpine:init', () => {
    window.Alpine.data('voiceRound', voiceRound);
    window.Alpine.data('roundRunner', roundRunner);
});
