const QUEUE_KEY = 'maintenance_upload_queue';

export async function uploadPhoto(base64Data, roundItemId) {
    const queue = getQueue();
    const id = `${roundItemId}_${Date.now()}`;
    queue[id] = { base64Data, roundItemId, attempts: 0 };
    saveQueue(queue);
    return attemptUpload(id);
}

async function attemptUpload(id, maxRetries = 3) {
    const queue = getQueue();
    const item = queue[id];
    if (!item) return;
    try {
        const res = await fetch('/maintenance/upload-photo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content,
            },
            body: JSON.stringify({
                image: item.base64Data,
                round_item_id: item.roundItemId,
            }),
        });
        if (!res.ok) throw new Error('fail');
        const data = await res.json();
        delete queue[id];
        saveQueue(queue);
        document.dispatchEvent(new CustomEvent('photo-uploaded', {
            detail: { path: data.path, roundItemId: item.roundItemId },
        }));
        return data.path;
    } catch {
        item.attempts++;
        if (item.attempts < maxRetries) {
            saveQueue(queue);
            await new Promise(r => setTimeout(r, 2000));
            return attemptUpload(id, maxRetries);
        }
        document.dispatchEvent(new CustomEvent('photo-upload-failed', {
            detail: { roundItemId: item.roundItemId },
        }));
    }
}

export function retryQueue() {
    Object.keys(getQueue()).forEach(id => attemptUpload(id));
}

function getQueue() {
    try {
        return JSON.parse(localStorage.getItem(QUEUE_KEY) || '{}');
    } catch {
        return {};
    }
}

function saveQueue(q) {
    localStorage.setItem(QUEUE_KEY, JSON.stringify(q));
}

window.addEventListener('load', retryQueue);
