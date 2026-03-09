 {{-- ============================================
     NLAH Chatbot Widget — Laravel Blade Partial
     Include this at the bottom of your layout,
     just before </body> in your main layout file:
     @include('partials.chatbot')
     ============================================ --}}

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">

<style>
  /* ─── CHAT BUBBLE BUTTON ─── */
  #nlah-chat-trigger {
    position: fixed;
    bottom: 2rem; right: 2rem;
    width: 60px; height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0e7490, #0369a1);
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 8px 32px rgba(14, 116, 144, 0.45);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    z-index: 9999;
  }
  #nlah-chat-trigger:hover {
    transform: scale(1.08);
    box-shadow: 0 12px 40px rgba(14, 116, 144, 0.6);
  }
  #nlah-chat-trigger svg { width: 26px; height: 26px; fill: #fff; }
  #nlah-chat-trigger .icon-close { display: none; }
  #nlah-chat-trigger.open .icon-chat { display: none; }
  #nlah-chat-trigger.open .icon-close { display: block; }

  /* Pulse ring */
  #nlah-chat-trigger::before {
    content: '';
    position: absolute; inset: -6px;
    border-radius: 50%;
    border: 2px solid rgba(14, 116, 144, 0.4);
    animation: nlah-pulse 2s ease-out infinite;
  }
  @keyframes nlah-pulse {
    0%   { opacity: 1; transform: scale(1); }
    100% { opacity: 0; transform: scale(1.5); }
  }

  /* ─── CHAT WINDOW ─── */
  #nlah-chat-window {
    position: fixed;
    bottom: 6rem; right: 2rem;
    width: 380px; height: 560px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    display: flex; flex-direction: column;
    overflow: hidden;
    box-shadow: 0 24px 80px rgba(0,0,0,0.15);
    z-index: 9998;
    transform: translateY(20px) scale(0.95);
    opacity: 0;
    pointer-events: none;
    transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), opacity 0.25s ease;
  }
  #nlah-chat-window.open {
    transform: translateY(0) scale(1);
    opacity: 1;
    pointer-events: all;
  }

  /* Header */
  .nlah-chat-header {
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, #0e7490, #0369a1);
    display: flex; align-items: center; gap: 0.75rem;
    flex-shrink: 0;
  }
  .nlah-chat-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 1.1rem;
  }
  .nlah-header-info { flex: 1; }
  .nlah-header-name {
    font-family: 'Syne', sans-serif;
    font-size: 0.9rem; font-weight: 700; color: #fff;
  }
  .nlah-header-status {
    font-size: 0.7rem; color: rgba(255,255,255,0.8);
    display: flex; align-items: center; gap: 0.3rem; margin-top: 2px;
  }
  .nlah-status-dot {
    width: 6px; height: 6px; border-radius: 50%; background: #86efac;
    animation: nlah-blink 2s ease-in-out infinite;
  }
  @keyframes nlah-blink { 0%,100%{opacity:1} 50%{opacity:0.4} }

  /* Messages */
  .nlah-messages {
    flex: 1; overflow-y: auto; padding: 1rem;
    display: flex; flex-direction: column; gap: 0.75rem;
    scroll-behavior: smooth; background: #f9fafb;
  }
  .nlah-messages::-webkit-scrollbar { width: 4px; }
  .nlah-messages::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }

  .nlah-msg {
    display: flex; gap: 0.5rem; align-items: flex-end;
    animation: nlah-msg-in 0.3s ease forwards;
  }
  @keyframes nlah-msg-in {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .nlah-msg.user { flex-direction: row-reverse; }

  .nlah-bubble {
    max-width: 78%;
    padding: 0.6rem 0.85rem;
    border-radius: 16px;
    font-size: 0.875rem; line-height: 1.55;
    word-break: break-word;
    font-family: 'DM Sans', sans-serif;
  }
  .nlah-msg.ai .nlah-bubble {
    background: #fff;
    border: 1px solid #e5e7eb;
    color: #374151;
    border-bottom-left-radius: 4px;
  }
  .nlah-msg.user .nlah-bubble {
    background: linear-gradient(135deg, #0e7490, #0369a1);
    color: #fff;
    border-bottom-right-radius: 4px;
  }
  .nlah-msg-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: linear-gradient(135deg, #0e7490, #0369a1);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; flex-shrink: 0;
  }

  /* Typing indicator */
  .nlah-typing {
    display: flex; gap: 4px; padding: 0.65rem 0.9rem;
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 16px; border-bottom-left-radius: 4px;
    width: fit-content;
  }
  .nlah-typing span {
    width: 6px; height: 6px; background: #0e7490; border-radius: 50%;
    animation: nlah-bounce 1.2s ease-in-out infinite;
  }
  .nlah-typing span:nth-child(2) { animation-delay: 0.2s; }
  .nlah-typing span:nth-child(3) { animation-delay: 0.4s; }
  @keyframes nlah-bounce {
    0%,80%,100% { transform: translateY(0); }
    40%          { transform: translateY(-6px); }
  }

  /* Suggestions */
  .nlah-suggestions {
    display: flex; flex-wrap: wrap; gap: 0.4rem;
    padding: 0.5rem 1rem 0.75rem; background: #f9fafb;
    flex-shrink: 0;
  }
  .nlah-chip {
    padding: 0.3rem 0.75rem;
    background: #e0f2fe;
    border: 1px solid #bae6fd;
    border-radius: 20px;
    font-size: 0.72rem; color: #0369a1;
    cursor: pointer; transition: background 0.15s;
    font-family: 'DM Sans', sans-serif;
  }
  .nlah-chip:hover { background: #bae6fd; }

  /* Input */
  .nlah-input-area {
    padding: 0.75rem 1rem;
    border-top: 1px solid #e5e7eb;
    display: flex; gap: 0.6rem; align-items: flex-end;
    background: #fff; flex-shrink: 0;
  }
  #nlah-user-input {
    flex: 1;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 0.6rem 0.9rem;
    color: #111827; font-family: 'DM Sans', sans-serif; font-size: 0.875rem;
    resize: none; outline: none; max-height: 120px;
    transition: border-color 0.2s; line-height: 1.4;
  }
  #nlah-user-input::placeholder { color: #9ca3af; }
  #nlah-user-input:focus { border-color: #0e7490; }

  #nlah-send-btn {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, #0e7490, #0369a1);
    border: none; cursor: pointer; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: transform 0.15s, opacity 0.15s;
  }
  #nlah-send-btn:hover { transform: scale(1.07); }
  #nlah-send-btn:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }
  #nlah-send-btn svg { width: 16px; height: 16px; fill: #fff; }

  @media (max-width: 440px) {
    #nlah-chat-window { width: calc(100vw - 2rem); right: 1rem; bottom: 5.5rem; }
  }
</style>

<!-- ── CHAT TRIGGER BUTTON ── -->
<button id="nlah-chat-trigger" aria-label="Open NLAH chat">
  <svg class="icon-chat" viewBox="0 0 24 24"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>
  <svg class="icon-close" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
</button>

<!-- ── CHAT WINDOW ── -->
<div id="nlah-chat-window" role="dialog" aria-label="NLAH Virtual Assistant">
  <div class="nlah-chat-header">
    <div class="nlah-chat-avatar">🏥</div>
    <div class="nlah-header-info">
      <div class="nlah-header-name">NLAH Virtual Assistant</div>
      <div class="nlah-header-status"><span class="nlah-status-dot"></span> Online · Here to help</div>
    </div>
  </div>

  <div class="nlah-messages" id="nlah-messages">
    <div class="nlah-msg ai">
      <div class="nlah-msg-avatar">🏥</div>
      <div class="nlah-bubble">Hello! 👋 Welcome to Northern Luzon Adventist Hospital. I'm your virtual assistant. I can help you with appointments, our services, departments, and more. How can I assist you today?</div>
    </div>
  </div>

  <div class="nlah-suggestions" id="nlah-suggestions">
    <span class="nlah-chip">Our services</span>
    <span class="nlah-chip">Book appointment</span>
    <span class="nlah-chip">Emergency contact</span>
    <span class="nlah-chip">Hospital hours</span>
  </div>

  <div class="nlah-input-area">
    <textarea id="nlah-user-input" rows="1" placeholder="Ask me anything…" maxlength="1000"></textarea>
    <button id="nlah-send-btn" aria-label="Send">
      <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
    </button>
  </div>
</div>

<script>
(function () {
  const API_KEY = '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';
  const MODEL   = 'meta-llama/llama-4-scout-17b-16e-instruct';
  const SYSTEM  = `You are a warm, friendly, and professional virtual assistant for Northern Luzon Adventist Hospital (NLAH), located at Artacho, Sison, Pangasinan. You speak in a caring, approachable tone — like a helpful hospital staff member.

HOSPITAL INFORMATION:
- Name: Northern Luzon Adventist Hospital, Inc. (NLAH)
- Address: Artacho, Sison, Pangasinan
- Phone: (075) 632-3200
- Website: www.nlahospital.com
- Operating Hours: Open 24 hours, 7 days a week

DEPARTMENTS & SERVICES:
1. IMAGING - Radiology, Ultrasound, CT-scan
2. LABORATORY - Clinical lab testing, blood work
3. PHARMACY - 24/7 prescription services
4. CAFETERIA - Healthy meals and beverages
5. DENTAL - General dentistry, oral surgery
6. CHAPLAINCY - Spiritual care and support
7. SHOCKWAVE THERAPY - Non-invasive pain management
8. AMBULANCE - Emergency medical transport
9. CARDIOLOGY - Cardiac care and rehabilitation
10. ENT - Ear, nose, and throat care
11. FAMILY MEDICINE - Comprehensive family healthcare
12. NEPHROLOGY - Kidney care and dialysis
13. NEUROLOGY - Brain and nervous system care
14. OBSTETRICS & GYNECOLOGY - Women's health and maternity
15. OPHTHALMOLOGY - Eye care and vision services
16. PEDIATRICS - Child and adolescent care
17. SURGERY - Advanced surgical procedures
18. PHYSICIAN CONSULTATION - Expert medical consultations
19. NUTRITION COUNSELING - Dietary guidance and planning
20. DIALYSIS CENTER - Coming soon

FOR APPOINTMENTS: Direct patients to call (075) 632-3200 or visit www.nlahospital.com.
FOR EMERGENCIES: Call (075) 632-3200 immediately or go to the Emergency Department. Ambulance available.

RULES:
- Always be warm, caring, and empathetic.
- Never diagnose or prescribe medication.
- For emergencies, always urge them to call or go to the ER immediately.
- If unsure, direct them to call (075) 632-3200.
- Keep answers concise and easy to understand.
- Reflect Adventist values of holistic care (body, mind, spirit) when appropriate.`;

  const trigger     = document.getElementById('nlah-chat-trigger');
  const chatWin     = document.getElementById('nlah-chat-window');
  const messagesEl  = document.getElementById('nlah-messages');
  const input       = document.getElementById('nlah-user-input');
  const sendBtn     = document.getElementById('nlah-send-btn');
  const chips       = document.querySelectorAll('.nlah-chip');

  let history = [];
  let isOpen  = false;

  trigger.addEventListener('click', () => {
    isOpen = !isOpen;
    chatWin.classList.toggle('open', isOpen);
    trigger.classList.toggle('open', isOpen);
    if (isOpen) setTimeout(() => input.focus(), 300);
  });

  chips.forEach(chip => {
    chip.addEventListener('click', () => {
      input.value = chip.textContent;
      sendMessage();
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
    avatar.textContent = role === 'ai' ? '🏥' : '🧑';
    const bubble = document.createElement('div');
    bubble.className = 'nlah-bubble';
    bubble.textContent = text;
    wrap.appendChild(avatar);
    wrap.appendChild(bubble);
    messagesEl.appendChild(wrap);
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  function showTyping() {
    const wrap = document.createElement('div');
    wrap.className = 'nlah-msg ai'; wrap.id = 'nlah-typing';
    const avatar = document.createElement('div');
    avatar.className = 'nlah-msg-avatar'; avatar.textContent = '🏥';
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

  let suggestionsShown = true;
  function hideSuggestions() {
    if (suggestionsShown) {
      document.getElementById('nlah-suggestions').style.display = 'none';
      suggestionsShown = false;
    }
  }

  async function sendMessage() {
    const text = input.value.trim();
    if (!text || sendBtn.disabled) return;

    hideSuggestions();
    appendMsg('user', text);
    history.push({ role: 'user', content: text });
    input.value = ''; input.style.height = 'auto';
    sendBtn.disabled = true;
    showTyping();

    try {
      const res = await fetch('https://api.groq.com/openai/v1/chat/completions', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${API_KEY}`
        },
        body: JSON.stringify({
          model: MODEL,
          messages: [{ role: 'system', content: SYSTEM }, ...history]
        })
      });

      const data = await res.json();
      removeTyping();

      if (data.choices && data.choices[0]?.message?.content) {
        const reply = data.choices[0].message.content;
        appendMsg('ai', reply);
        history.push({ role: 'assistant', content: reply });
      } else if (data.error) {
        appendMsg('ai', '❌ ' + data.error.message);
        history.pop();
      } else {
        appendMsg('ai', '❌ Unexpected error. Please try again.');
        history.pop();
      }
    } catch (err) {
      removeTyping();
      appendMsg('ai', '❌ Network error: ' + err.message);
      history.pop();
    }

    sendBtn.disabled = false;
    input.focus();
  }
})();
</script>