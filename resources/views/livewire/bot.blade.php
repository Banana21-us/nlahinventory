{{-- ============================================
     NLAH Chatbot Widget — matches /nlah/home theme
     ============================================ --}}

<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

<style>
  /* ─── BASE ─── */
  #nlah-chat-trigger,
  #nlah-chat-window,
  #nlah-chat-window * {
    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    box-sizing: border-box;
  }

  /* ─── TRIGGER BUTTON ─── */
  #nlah-chat-trigger {
    position: fixed;
    bottom: 2rem; right: 2rem;
    width: 56px; height: 56px;
    border-radius: 50%;
    background: #18181b;
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 24px rgba(0,0,0,0.18);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    z-index: 9999;
  }
  #nlah-chat-trigger:hover {
    transform: scale(1.07);
    box-shadow: 0 8px 32px rgba(0,0,0,0.22);
  }
  #nlah-chat-trigger svg { width: 22px; height: 22px; fill: #fff; }
  #nlah-chat-trigger .icon-close { display: none; }
  #nlah-chat-trigger.open .icon-chat  { display: none; }
  #nlah-chat-trigger.open .icon-close { display: block; }

  /* ─── CHAT WINDOW ─── */
  #nlah-chat-window {
    position: fixed;
    bottom: 5.5rem; right: 2rem;
    width: 380px; height: 560px;
    background: #fff;
    border: 1px solid #e4e4e7;
    border-radius: 20px;
    display: flex; flex-direction: column;
    overflow: hidden;
    box-shadow: 0 16px 64px rgba(0,0,0,0.12);
    z-index: 9998;
    transform: translateY(16px) scale(0.96);
    opacity: 0;
    pointer-events: none;
    transition: transform 0.28s cubic-bezier(0.34,1.56,0.64,1), opacity 0.2s ease;
  }
  #nlah-chat-window.open {
    transform: translateY(0) scale(1);
    opacity: 1;
    pointer-events: all;
  }

  /* ─── HEADER ─── */
  .nlah-chat-header {
    padding: 1rem 1.25rem;
    background: #18181b;
    display: flex; align-items: center; gap: 0.75rem;
    flex-shrink: 0;
  }
  .nlah-chat-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #e8dec9;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 1rem; color: #5a4e3a;
    font-weight: 600;
  }
  .nlah-header-info { flex: 1; }
  .nlah-header-name {
    font-size: 0.875rem; font-weight: 600; color: #fff;
    display: flex; align-items: center; gap: 0.5rem;
    letter-spacing: -0.01em;
  }
  .nlah-header-status {
    font-size: 0.7rem; color: rgba(255,255,255,0.55);
    display: flex; align-items: center; gap: 0.3rem; margin-top: 2px;
  }
  .nlah-status-dot {
    width: 6px; height: 6px; border-radius: 50%; background: #86efac;
    animation: nlah-blink 2.4s ease-in-out infinite;
  }
  @keyframes nlah-blink { 0%,100%{opacity:1} 50%{opacity:0.35} }

  .nlah-feedback-btn {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.8);
    font-size: 0.68rem; font-weight: 500;
    padding: 0.2rem 0.55rem;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s;
    margin-left: auto;
  }
  .nlah-feedback-btn:hover { background: rgba(255,255,255,0.18); }

  /* ─── MESSAGES ─── */
  .nlah-messages {
    flex: 1; overflow-y: auto; padding: 1rem;
    display: flex; flex-direction: column; gap: 0.75rem;
    scroll-behavior: smooth; background: #fafafa;
  }
  .nlah-messages::-webkit-scrollbar { width: 3px; }
  .nlah-messages::-webkit-scrollbar-thumb { background: #d4d4d8; border-radius: 2px; }

  .nlah-msg {
    display: flex; gap: 0.5rem; align-items: flex-end;
    animation: nlah-msg-in 0.25s ease forwards;
  }
  @keyframes nlah-msg-in {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .nlah-msg.user { flex-direction: row-reverse; }

  .nlah-bubble {
    max-width: 78%;
    padding: 0.6rem 0.875rem;
    border-radius: 14px;
    font-size: 0.875rem; line-height: 1.55;
    word-break: break-word;
    white-space: pre-wrap;
    font-weight: 400;
  }
  .nlah-msg.ai .nlah-bubble {
    background: #fff;
    border: 1px solid #e4e4e7;
    color: #3f3f46;
    border-bottom-left-radius: 3px;
  }
  .nlah-msg.user .nlah-bubble {
    background: #18181b;
    color: #fff;
    border-bottom-right-radius: 3px;
  }
  .nlah-msg-avatar {
    width: 26px; height: 26px; border-radius: 50%;
    background: #e8dec9;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 600; color: #5a4e3a;
    flex-shrink: 0;
  }

  /* ─── TYPING ─── */
  .nlah-typing {
    display: flex; gap: 4px; padding: 0.65rem 0.9rem;
    background: #fff; border: 1px solid #e4e4e7;
    border-radius: 14px; border-bottom-left-radius: 3px;
    width: fit-content;
  }
  .nlah-typing span {
    width: 5px; height: 5px; background: #71717a; border-radius: 50%;
    animation: nlah-bounce 1.2s ease-in-out infinite;
  }
  .nlah-typing span:nth-child(2) { animation-delay: 0.18s; }
  .nlah-typing span:nth-child(3) { animation-delay: 0.36s; }
  @keyframes nlah-bounce {
    0%,80%,100% { transform: translateY(0); }
    40%         { transform: translateY(-5px); }
  }

  /* ─── SUGGESTIONS ─── */
  .nlah-suggestions {
    display: flex; flex-wrap: wrap; gap: 0.4rem;
    padding: 0.5rem 1rem 0.65rem; background: #fafafa;
    flex-shrink: 0; border-top: 1px solid #f4f4f5;
  }
  .nlah-chip {
    padding: 0.28rem 0.7rem;
    background: #e8dec9;
    border: 1px solid #ddd0b8;
    border-radius: 20px;
    font-size: 0.72rem; font-weight: 500; color: #5a4e3a;
    cursor: pointer; transition: background 0.15s;
  }
  .nlah-chip:hover { background: #ddd0b8; }

  /* ─── INPUT ─── */
  .nlah-input-area {
    padding: 0.75rem 1rem;
    border-top: 1px solid #e4e4e7;
    display: flex; gap: 0.5rem; align-items: flex-end;
    background: #fff; flex-shrink: 0;
  }
  #nlah-user-input {
    flex: 1;
    background: #f4f4f5;
    border: 1px solid #e4e4e7;
    border-radius: 10px;
    padding: 0.55rem 0.875rem;
    color: #18181b; font-size: 0.875rem; font-weight: 400;
    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    resize: none; outline: none; max-height: 120px;
    transition: border-color 0.18s; line-height: 1.4;
  }
  #nlah-user-input::placeholder { color: #a1a1aa; }
  #nlah-user-input:focus { border-color: #71717a; background: #fff; }

  #nlah-send-btn {
    width: 36px; height: 36px; border-radius: 9px;
    background: #18181b;
    border: none; cursor: pointer; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: transform 0.15s, opacity 0.15s;
  }
  #nlah-send-btn:hover { transform: scale(1.06); }
  #nlah-send-btn:disabled { opacity: 0.35; cursor: not-allowed; transform: none; }
  #nlah-send-btn svg { width: 15px; height: 15px; fill: #fff; }

  /* ─── FEEDBACK OVERLAY ─── */
  #nlah-feedback-container {
    display: none;
    position: absolute; top: 68px; left: 0; right: 0; bottom: 0;
    background: #fafafa;
    z-index: 1000;
    overflow-y: auto;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    padding: 1.5rem;
  }
  .nlah-fb-title {
    font-size: 1rem; font-weight: 600; color: #18181b;
    margin-bottom: 1.25rem; text-align: center;
    letter-spacing: -0.01em;
  }
  .nlah-fb-label {
    display: block; font-size: 0.72rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: #71717a; margin-bottom: 0.4rem;
  }
  .nlah-fb-input {
    width: 100%; padding: 0.6rem 0.875rem;
    border: 1px solid #e4e4e7; border-radius: 10px;
    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    font-size: 0.875rem; color: #18181b;
    background: #fff; margin-bottom: 0.875rem;
    transition: border-color 0.18s; outline: none;
  }
  .nlah-fb-input:focus { border-color: #71717a; }
  .nlah-fb-submit {
    width: 100%; padding: 0.75rem;
    background: #18181b; color: #fff;
    border: none; border-radius: 10px;
    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    font-weight: 600; font-size: 0.9rem;
    cursor: pointer; margin-bottom: 0.75rem;
    transition: opacity 0.15s;
  }
  .nlah-fb-submit:hover { opacity: 0.88; }
  .nlah-fb-submit:disabled { opacity: 0.4; cursor: not-allowed; }
  .nlah-fb-back {
    display: block; text-align: center;
    color: #71717a; font-size: 0.82rem;
    cursor: pointer; text-decoration: underline;
    text-underline-offset: 3px;
  }

  @media (max-width: 440px) {
    #nlah-chat-window { width: calc(100vw - 2rem); right: 1rem; bottom: 5rem; }
    #nlah-chat-trigger { bottom: 1.5rem; right: 1.5rem; }
  }
</style>

<!-- Trigger -->
<button id="nlah-chat-trigger" aria-label="Open NLAH chat">
  <svg class="icon-chat" viewBox="0 0 24 24"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>
  <svg class="icon-close" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
</button>

<!-- Chat Window -->
<div id="nlah-chat-window" role="dialog" aria-label="NLAH Virtual Assistant">
  <div class="nlah-chat-header">
    <div class="nlah-chat-avatar">N</div>
    <div class="nlah-header-info">
      <div class="nlah-header-name">
        NLAH Assistant
        <button class="nlah-feedback-btn" onclick="nlahShowFeedback()">Feedback</button>
      </div>
      <div class="nlah-header-status"><span class="nlah-status-dot"></span> Online · Here to help</div>
    </div>
  </div>

  <div class="nlah-messages" id="nlah-messages">
    <div class="nlah-msg ai">
      <div class="nlah-msg-avatar">N</div>
      <div class="nlah-bubble">Hello! 👋 Welcome to Northern Luzon Adventist Hospital. How can I help you today?</div>
    </div>
  </div>

  <!-- Feedback Overlay -->
  <div id="nlah-feedback-container">
    <p class="nlah-fb-title">Share Your Feedback</p>

    <div id="nlah-stars" style="display:flex; justify-content:center; gap:8px; margin-bottom:1.25rem;">
      <span data-val="1" onclick="nlahSetRating(1)" style="font-size:2rem; cursor:pointer; color:#d4d4d8; transition:color 0.15s;">★</span>
      <span data-val="2" onclick="nlahSetRating(2)" style="font-size:2rem; cursor:pointer; color:#d4d4d8; transition:color 0.15s;">★</span>
      <span data-val="3" onclick="nlahSetRating(3)" style="font-size:2rem; cursor:pointer; color:#d4d4d8; transition:color 0.15s;">★</span>
      <span data-val="4" onclick="nlahSetRating(4)" style="font-size:2rem; cursor:pointer; color:#d4d4d8; transition:color 0.15s;">★</span>
      <span data-val="5" onclick="nlahSetRating(5)" style="font-size:2rem; cursor:pointer; color:#d4d4d8; transition:color 0.15s;">★</span>
    </div>

    <label class="nlah-fb-label">Your name (optional)</label>
    <input type="text" id="nlah-fb-name" placeholder="e.g. Juan dela Cruz" class="nlah-fb-input">

    <label class="nlah-fb-label">Your feedback</label>
    <textarea id="nlah-fb-comment" rows="4" placeholder="Tell us about your experience..." class="nlah-fb-input" style="resize:vertical;"></textarea>

    <button id="nlah-fb-submit-btn" class="nlah-fb-submit" onclick="nlahSubmitFeedback()">Submit Feedback</button>
    <span class="nlah-fb-back" onclick="nlahHideFeedback()">← Back to chat</span>
  </div>

  <div class="nlah-suggestions" id="nlah-suggestions">
    <span class="nlah-chip">Our services</span>
    <span class="nlah-chip">Book appointment</span>
    <span class="nlah-chip">Emergency contact</span>
    <span class="nlah-chip">Hospital hours</span>
    <span class="nlah-chip">Leave feedback</span>
  </div>

  <div class="nlah-input-area" id="nlah-input-area">
    <textarea id="nlah-user-input" rows="1" placeholder="Type your message..." maxlength="1000"></textarea>
    <button id="nlah-send-btn" aria-label="Send">
      <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
    </button>
  </div>
</div>

<script>
(function () {
  const trigger        = document.getElementById('nlah-chat-trigger');
  const chatWin        = document.getElementById('nlah-chat-window');
  const messagesEl     = document.getElementById('nlah-messages');
  const input          = document.getElementById('nlah-user-input');
  const sendBtn        = document.getElementById('nlah-send-btn');
  const chips          = document.querySelectorAll('.nlah-chip');
  const feedbackCont   = document.getElementById('nlah-feedback-container');
  const suggestionsDiv = document.getElementById('nlah-suggestions');
  const inputArea      = document.getElementById('nlah-input-area');

  let isOpen         = false;
  let selectedRating = 0;
  let history        = [];

  trigger.addEventListener('click', () => {
    isOpen = !isOpen;
    chatWin.classList.toggle('open', isOpen);
    trigger.classList.toggle('open', isOpen);
    if (isOpen) setTimeout(() => input.focus(), 300);
  });

  chips.forEach(chip => {
    chip.addEventListener('click', () => {
      if (chip.textContent.trim() === 'Leave feedback') {
        nlahShowFeedback();
      } else {
        input.value = chip.textContent.trim();
        sendMessage();
      }
    });
  });

  input.addEventListener('input', () => {
    input.style.height = 'auto';
    input.style.height = Math.min(input.scrollHeight, 120) + 'px';
  });

  input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
  });

  sendBtn.addEventListener('click', sendMessage);

  function appendMsg(role, text) {
    const wrap   = document.createElement('div');
    wrap.className = `nlah-msg ${role}`;
    const avatar = document.createElement('div');
    avatar.className = 'nlah-msg-avatar';
    avatar.textContent = role === 'ai' ? 'N' : '👤';
    const bubble = document.createElement('div');
    bubble.className = 'nlah-bubble';
    bubble.textContent = text;
    if (role === 'user') { wrap.appendChild(bubble); wrap.appendChild(avatar); }
    else                 { wrap.appendChild(avatar); wrap.appendChild(bubble); }
    messagesEl.appendChild(wrap);
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  function showTyping() {
    const wrap = document.createElement('div');
    wrap.className = 'nlah-msg ai'; wrap.id = 'nlah-typing';
    const avatar = document.createElement('div');
    avatar.className = 'nlah-msg-avatar'; avatar.textContent = 'N';
    const ind = document.createElement('div');
    ind.className = 'nlah-typing';
    ind.innerHTML = '<span></span><span></span><span></span>';
    wrap.appendChild(avatar); wrap.appendChild(ind);
    messagesEl.appendChild(wrap);
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  function removeTyping() {
    const el = document.getElementById('nlah-typing');
    if (el) el.remove();
  }

  async function sendMessage() {
    const text = input.value.trim();
    if (!text || sendBtn.disabled) return;
    appendMsg('user', text);
    history.push({ role: 'user', content: text });
    input.value = ''; input.style.height = 'auto';
    sendBtn.disabled = true; showTyping();
    try {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      const res  = await fetch('/nlah/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf ?? '' },
        body: JSON.stringify({ messages: history })
      });
      removeTyping();
      let data = {};
      try { data = await res.json(); } catch { /* non-JSON response */ }
      if (data.reply) {
        appendMsg('ai', data.reply);
        history.push({ role: 'assistant', content: data.reply });
        if (history.length > 20) history = history.slice(-20);
      } else {
        appendMsg('ai', data.error ?? `Server error (${res.status}). Please try again.`);
      }
    } catch {
      removeTyping();
      appendMsg('ai', 'Could not reach the server. Please check your connection.');
    }
    sendBtn.disabled = false; input.focus();
  }

  // ── Feedback ──
  window.nlahShowFeedback = function () {
    selectedRating = 0;
    document.querySelectorAll('#nlah-stars span').forEach(s => s.style.color = '#d4d4d8');
    document.getElementById('nlah-fb-name').value    = '';
    document.getElementById('nlah-fb-comment').value = '';
    const btn = document.getElementById('nlah-fb-submit-btn');
    btn.disabled = false; btn.textContent = 'Submit Feedback';
    messagesEl.style.display     = 'none';
    suggestionsDiv.style.display = 'none';
    inputArea.style.display      = 'none';
    feedbackCont.style.display   = 'block';
  };

  window.nlahHideFeedback = function () {
    feedbackCont.style.display   = 'none';
    messagesEl.style.display     = 'flex';
    suggestionsDiv.style.display = 'flex';
    inputArea.style.display      = 'flex';
  };

  window.nlahSetRating = function (val) {
    selectedRating = val;
    document.querySelectorAll('#nlah-stars span').forEach(s => {
      s.style.color = parseInt(s.dataset.val) <= val ? '#f59e0b' : '#d4d4d8';
    });
  };

  window.nlahSubmitFeedback = function () {
    const name    = document.getElementById('nlah-fb-name').value.trim() || 'Guest';
    const comment = document.getElementById('nlah-fb-comment').value.trim();
    const btn     = document.getElementById('nlah-fb-submit-btn');
    if (!comment)          { alert('Please enter your feedback.'); return; }
    if (!selectedRating)   { alert('Please select a star rating.'); return; }
    btn.disabled = true; btn.textContent = 'Submitting…';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    fetch('/nlah/feedback/submit', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf ?? '' },
      body: JSON.stringify({ name, comment, rating: selectedRating })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        nlahHideFeedback();
        appendMsg('ai', 'Thank you for your feedback! 😊 We really appreciate you sharing your experience.');
      } else {
        alert('Error: ' + (data.message || 'Could not submit feedback.'));
        btn.disabled = false; btn.textContent = 'Submit Feedback';
      }
    })
    .catch(() => {
      alert('Network error. Please try again.');
      btn.disabled = false; btn.textContent = 'Submit Feedback';
    });
  };

  window.showFeedbackForm = window.nlahShowFeedback;
})();
</script>
