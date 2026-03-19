{{-- ============================================
     NLAH Chatbot Widget — Laravel Blade Partial
     Include this at the bottom of your layout
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
    display: flex; align-items: center; gap: 0.5rem;
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

  /* Feedback Button */
  .nlah-feedback-btn {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    font-size: 0.7rem;
    padding: 0.2rem 0.6rem;
    border-radius: 12px;
    cursor: pointer;
    transition: background 0.15s;
    font-family: 'DM Sans', sans-serif;
    margin-left: auto;
    margin-left: auto;
  }
  .nlah-feedback-btn:hover {
    background: rgba(255,255,255,0.3);
  }

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

  /* Feedback Form - Simple */
  .nlah-feedback-form {
    background: white;
    border-radius: 12px;
    padding: 1.2rem;
    margin-bottom: 900px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  .nlah-feedback-title {
    font-family: 'Syne', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    color: #0369a1;
    margin-bottom: 1rem;
    text-align: center;
  }
  .nlah-star-rating {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
  }
  .nlah-star {
    font-size: 2rem;
    cursor: pointer;
    color: #d1d5db;
  }
  .nlah-star.selected {
    color: #fbbf24;
  }
  .nlah-feedback-input, .nlah-feedback-name {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.875rem;
    margin-bottom: 1rem;
  }
  .nlah-feedback-input:focus, .nlah-feedback-name:focus {
    outline: none;
    border-color: #0369a1;
  }
  .nlah-feedback-submit {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(135deg, #0e7490, #0369a1);
    color: white;
    border: none;
    border-radius: 8px;
    font-family: 'Syne', sans-serif;
    font-weight: 600;
    cursor: pointer;
  }
  .nlah-back-btn {
    display: block;
    text-align: center;
    margin-top: 0.75rem;
    color: #6b7280;
    font-size: 0.75rem;
    cursor: pointer;
    text-decoration: underline;
  }

  @media (max-width: 440px) {
    #nlah-chat-window { width: calc(100vw - 2rem); right: 1rem; bottom: 5.5rem; }
  }
</style>

<!-- Chat Trigger Button -->
<button id="nlah-chat-trigger" aria-label="Open NLAH chat">
  <svg class="icon-chat" viewBox="0 0 24 24"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>
  <svg class="icon-close" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
</button>

<!-- Chat Window -->
<div id="nlah-chat-window" role="dialog" aria-label="NLAH Virtual Assistant">
  <div class="nlah-chat-header">
    <div class="nlah-chat-avatar">🏥</div>
    <div class="nlah-header-info">
      <div class="nlah-header-name">
        NLAH Virtual Assistant
        <span class="nlah-feedback-btn" onclick="showFeedbackForm()">Feedback</span>
      </div>
      <div class="nlah-header-status"><span class="nlah-status-dot"></span> Online · Here to help</div>
    </div>
  </div>

  <div class="nlah-messages" id="nlah-messages">
    <div class="nlah-msg ai">
      <div class="nlah-msg-avatar">🏥</div>
      <div class="nlah-bubble">Hello! 👋 Welcome to Northern Luzon Adventist Hospital. I'm your virtual assistant. How can I help you today?</div>
    </div>
  </div>

  <!-- Feedback Form Container -->
  <div id="nlah-feedback-container" style="display: none; position: absolute; top: 72px; left: 0; right: 0; bottom: 0; background: white; z-index: 1000; overflow-y: auto; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;"></div>

  <div class="nlah-suggestions" id="nlah-suggestions">
    <span class="nlah-chip">Our services</span>
    <span class="nlah-chip">Book appointment</span>
    <span class="nlah-chip">Emergency contact</span>
    <span class="nlah-chip">Hospital hours</span>
    <span class="nlah-chip">Leave feedback</span>
  </div>

  <div class="nlah-input-area">
    <textarea id="nlah-user-input" rows="1" placeholder="Type your message..." maxlength="1000"></textarea>
    <button id="nlah-send-btn" aria-label="Send">
      <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
    </button>
  </div>
</div>

<script>
(function () {
  const API_KEY = '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';
  const MODEL   = 'meta-llama/llama-4-scout-17b-16e-instruct';
  const SYSTEM  = `You are a friendly virtual assistant for Northern Luzon Adventist Hospital. Keep responses brief and helpful.`;

  const trigger = document.getElementById('nlah-chat-trigger');
  const chatWin = document.getElementById('nlah-chat-window');
  const messagesEl = document.getElementById('nlah-messages');
  const input = document.getElementById('nlah-user-input');
  const sendBtn = document.getElementById('nlah-send-btn');
  const chips = document.querySelectorAll('.nlah-chip');
  const feedbackContainer = document.getElementById('nlah-feedback-container');
  const suggestionsDiv = document.getElementById('nlah-suggestions');

  let isOpen = false;
  let selectedRating = 0;

  // Toggle chat window
  trigger.addEventListener('click', () => {
    isOpen = !isOpen;
    chatWin.classList.toggle('open', isOpen);
    trigger.classList.toggle('open', isOpen);
    if (isOpen) setTimeout(() => input.focus(), 300);
  });

  // Suggestion chips
  chips.forEach(chip => {
    chip.addEventListener('click', () => {
      if (chip.textContent === 'Leave feedback') {
        showFeedbackForm();
      } else {
        input.value = chip.textContent;
        sendMessage();
      }
    });
  });

  // Auto-resize textarea
  input.addEventListener('input', () => {
    input.style.height = 'auto';
    input.style.height = Math.min(input.scrollHeight, 120) + 'px';
  });

  // Send on Enter (but allow Shift+Enter for new line)
  input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  sendBtn.addEventListener('click', sendMessage);

  // Simple function to add message to chat
  function appendMsg(role, text) {
    const wrap = document.createElement('div');
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

  // Show typing indicator
  function showTyping() {
    const wrap = document.createElement('div');
    wrap.className = 'nlah-msg ai';
    wrap.id = 'nlah-typing';
    
    const avatar = document.createElement('div');
    avatar.className = 'nlah-msg-avatar';
    avatar.textContent = '🏥';
    
    const ind = document.createElement('div');
    ind.className = 'nlah-typing';
    ind.innerHTML = '<span></span><span></span><span></span>';
    
    wrap.appendChild(avatar);
    wrap.appendChild(ind);
    messagesEl.appendChild(wrap);
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  // Remove typing indicator
  function removeTyping() {
    const el = document.getElementById('nlah-typing');
    if (el) el.remove();
  }

  // Send message to API
  async function sendMessage() {
    const text = input.value.trim();
    if (!text || sendBtn.disabled) return;

    appendMsg('user', text);
    input.value = '';
    input.style.height = 'auto';
    sendBtn.disabled = true;
    showTyping();

    try {
      const response = await fetch('https://api.groq.com/openai/v1/chat/completions', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${API_KEY}`
        },
        body: JSON.stringify({
          model: MODEL,
          messages: [{ role: 'system', content: SYSTEM }, { role: 'user', content: text }]
        })
      });

      const data = await response.json();
      removeTyping();

      if (data.choices && data.choices[0]?.message?.content) {
        appendMsg('ai', data.choices[0].message.content);
      } else {
        appendMsg('ai', 'Sorry, I had trouble responding. Please try again.');
      }
    } catch (err) {
      removeTyping();
      appendMsg('ai', 'Network error. Please check your connection.');
    }

    sendBtn.disabled = false;
    input.focus();
  }

  // Make functions globally available
  window.showFeedbackForm = function() {
    suggestionsDiv.style.display = 'none';
    document.querySelector('.nlah-input-area').style.display = 'none';
    
    // Hide all messages
    const messages = document.getElementById('nlah-messages');
    messages.style.display = 'none';
    
    feedbackContainer.style.display = 'block';
    feedbackContainer.innerHTML = `
      <div style="height: 100%; display: flex; flex-direction: column; padding: 24px; background: white;">
        <div style="font-family: 'Syne', sans-serif; font-size: 1.25rem; font-weight: 600; color: #0369a1; margin-bottom: 24px; text-align: center;">Share Your Feedback</div>
        
        <div style="display: flex; justify-content: center; gap: 12px; margin-bottom: 24px;">
          <span class="nlah-star" onclick="setRating(1)" style="font-size: 2.5rem; cursor: pointer; color: #d1d5db; transition: color 0.2s;">★</span>
          <span class="nlah-star" onclick="setRating(2)" style="font-size: 2.5rem; cursor: pointer; color: #d1d5db; transition: color 0.2s;">★</span>
          <span class="nlah-star" onclick="setRating(3)" style="font-size: 2.5rem; cursor: pointer; color: #d1d5db; transition: color 0.2s;">★</span>
          <span class="nlah-star" onclick="setRating(4)" style="font-size: 2.5rem; cursor: pointer; color: #d1d5db; transition: color 0.2s;">★</span>
          <span class="nlah-star" onclick="setRating(5)" style="font-size: 2.5rem; cursor: pointer; color: #d1d5db; transition: color 0.2s;">★</span>
        </div>
        
        <input type="text" id="feedback-name" placeholder="Your name (optional)" value="Guest" style="width: 100%; padding: 14px; border: 1px solid #e5e7eb; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 0.95rem; margin-bottom: 16px; box-sizing: border-box;">
        
        <textarea id="feedback-comment" rows="5" placeholder="Tell us about your experience..." style="width: 100%; padding: 14px; border: 1px solid #e5e7eb; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 0.95rem; margin-bottom: 24px; box-sizing: border-box; resize: vertical; flex: 1; min-height: 120px;"></textarea>
        
        <button onclick="submitFeedback()" style="width: 100%; padding: 16px; background: linear-gradient(135deg, #0e7490, #0369a1); color: white; border: none; border-radius: 10px; font-family: 'Syne', sans-serif; font-weight: 600; font-size: 1rem; cursor: pointer; margin-bottom: 12px;">Submit Feedback</button>
        
        <span onclick="hideFeedbackForm()" style="display: block; text-align: center; color: #6b7280; font-size: 0.9rem; cursor: pointer; text-decoration: underline; padding: 8px;">← Back to chat</span>
      </div>
    `;
    selectedRating = 0;
};

  window.setRating = function(rating) {
    selectedRating = rating;
    const stars = document.querySelectorAll('.nlah-star');
    stars.forEach((star, index) => {
      if (index < rating) {
        star.classList.add('selected');
      } else {
        star.classList.remove('selected');
      }
    });
  };

  window.submitFeedback = function() {
    const name = document.getElementById('feedback-name').value.trim() || 'Guest';
    const comment = document.getElementById('feedback-comment').value.trim();
    
    if (!comment) {
      alert('Please enter your feedback');
      return;
    }
    
    if (selectedRating === 0) {
      alert('Please select a rating');
      return;
    }
    
    const submitBtn = document.querySelector('.nlah-feedback-submit');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch('/nlah/feedback/submit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        name: name,
        comment: comment,
        rating: selectedRating
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        hideFeedbackForm();
        appendMsg('ai', 'Thank you for your feedback! 😊');
      } else {
        alert('Error: ' + (data.message || 'Could not submit feedback'));
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit Feedback';
      }
    })
    .catch(error => {
      alert('Error submitting feedback');
      submitBtn.disabled = false;
      submitBtn.textContent = 'Submit Feedback';
    });
  };

  window.hideFeedbackForm = function() {
    feedbackContainer.style.display = 'none';
    feedbackContainer.innerHTML = '';
    
    // Show messages again
    const messages = document.getElementById('nlah-messages');
    messages.style.display = 'flex';
    
    suggestionsDiv.style.display = 'flex';
    document.querySelector('.nlah-input-area').style.display = 'flex';
};
})();
</script>