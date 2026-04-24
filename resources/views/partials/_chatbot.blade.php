{{-- ============================================================
     APM Chatbot Widget — Floating Popup (pojok kanan bawah)
     Include di _navbar.blade.php agar muncul di semua halaman
     ============================================================ --}}

<style>
    /* ── Chatbot container ─────────────────────────── */
    #apmChatWidget {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        font-family: 'Poppins', sans-serif;
    }

    /* ── Toggle bubble button ─────────────────────── */
    #apmChatToggle {
        width: 64px; height: 64px;
        background: linear-gradient(135deg, #CD422B, #a83220);
        border-radius: 50%;
        border: none;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 25px rgba(205,66,43,.45);
        transition: transform .25s, box-shadow .25s;
        position: relative;
    }
    #apmChatToggle:hover { transform: scale(1.08); box-shadow: 0 12px 32px rgba(205,66,43,.55); }
    #apmChatToggle .pulse-ring {
        position: absolute; inset: -6px;
        border-radius: 50%;
        border: 2px solid rgba(205,66,43,.4);
        animation: chatPulse 2s infinite;
    }
    @keyframes chatPulse {
        0%   { transform: scale(.9); opacity: 1; }
        70%  { transform: scale(1.25); opacity: 0; }
        100% { transform: scale(.9); opacity: 0; }
    }

    /* ── Chat popup ────────────────────────────────── */
    #apmChatBox {
        position: absolute;
        bottom: 76px; right: 0;
        width: 420px;
        max-height: 620px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,.15), 0 4px 16px rgba(0,0,0,.08);
        display: flex; flex-direction: column;
        overflow: hidden;
        transform: scale(.85) translateY(20px);
        opacity: 0;
        pointer-events: none;
        transition: transform .3s cubic-bezier(.34,1.56,.64,1), opacity .25s;
        transform-origin: bottom right;
    }
    #apmChatBox.open {
        transform: scale(1) translateY(0);
        opacity: 1;
        pointer-events: all;
    }

    /* ── Header ─────────────────────────────────────── */
    #apmChatHeader {
        background: linear-gradient(135deg, #CD422B 0%, #a83220 100%);
        padding: 14px 16px;
        display: flex; align-items: center; gap: 10px;
        flex-shrink: 0;
    }
    #apmChatHeader .bot-avatar {
        width: 38px; height: 38px;
        background: rgba(255,255,255,.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    #apmChatHeader .bot-info { flex: 1; overflow: hidden; }
    #apmChatHeader .bot-name { font-weight: 700; color: #fff; font-size: 14px; }
    #apmChatHeader .bot-status {
        font-size: 11px; color: rgba(255,255,255,.75);
        display: flex; align-items: center; gap: 4px;
    }
    #apmChatHeader .status-dot {
        width: 6px; height: 6px;
        background: #7DF294; border-radius: 50%;
        animation: blink 1.8s infinite;
    }
    @keyframes blink { 0%,100% { opacity: 1; } 50% { opacity: .3; } }

    /* ── Messages area ─────────────────────────────── */
    #apmChatMessages {
        flex: 1;
        overflow-y: auto;
        padding: 14px 12px 6px;
        display: flex; flex-direction: column; gap: 10px;
        scroll-behavior: smooth;
    }
    #apmChatMessages::-webkit-scrollbar { width: 4px; }
    #apmChatMessages::-webkit-scrollbar-track { background: #f5f5f5; border-radius: 4px; }
    #apmChatMessages::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }

    /* ── Bubble styles ─────────────────────────────── */
    .chat-bubble-wrap { display: flex; flex-direction: column; }
    .chat-bubble-wrap.user { align-items: flex-end; }
    .chat-bubble-wrap.bot  { align-items: flex-start; }

    .chat-bubble {
        max-width: 82%;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.55;
        word-break: break-word;
        white-space: pre-wrap;
        animation: bubbleIn .25s ease;
    }
    @keyframes bubbleIn { from { transform: translateY(8px); opacity: 0; } to { transform: none; opacity: 1; } }

    .chat-bubble.bot  {
        background: #F5F5F5;
        color: #1f2937;
        border-bottom-left-radius: 4px;
    }
    .chat-bubble.user {
        background: linear-gradient(135deg, #CD422B, #a83220);
        color: #fff;
        border-bottom-right-radius: 4px;
    }
    /* Bold markdown **text** */
    .chat-bubble b, .chat-bubble strong { font-weight: 700; }

    /* ── Quick reply buttons ────────────────────────── */
    .chat-btn-group {
        display: flex; flex-direction: column; gap: 6px;
        margin-top: 2px;
        max-width: 90%;
        animation: bubbleIn .3s ease;
    }
    .chat-qr-btn {
        background: #fff;
        border: 1.5px solid #e5e7eb;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        padding: 9px 16px;
        border-radius: 20px;
        cursor: pointer;
        text-align: left;
        transition: all .18s;
        font-family: 'Poppins', sans-serif;
        display: flex; align-items: center; gap: 6px;
    }
    .chat-qr-btn:hover {
        border-color: #CD422B;
        color: #CD422B;
        background: #fff5f4;
        transform: translateX(3px);
    }
    .chat-qr-btn.link-btn {
        border-color: #CD422B;
        color: #CD422B;
    }
    .chat-qr-btn.link-btn:hover {
        background: #CD422B;
        color: #fff;
    }

    /* ── Typing indicator ──────────────────────────── */
    .typing-dots { display: flex; gap: 4px; align-items: center; padding: 4px 2px; }
    .typing-dots span {
        width: 7px; height: 7px;
        background: #bbb; border-radius: 50%;
        animation: typingBounce 1s infinite;
    }
    .typing-dots span:nth-child(2) { animation-delay: .15s; }
    .typing-dots span:nth-child(3) { animation-delay: .30s; }
    @keyframes typingBounce { 0%,80%,100% { transform: translateY(0); } 40% { transform: translateY(-6px); } }

    /* ── Input area ────────────────────────────────── */
    #apmChatInputArea {
        border-top: 1px solid #f0f0f0;
        padding: 10px 12px;
        display: flex; gap: 8px; align-items: center;
        background: #fafafa;
        flex-shrink: 0;
    }
    #apmChatInput {
        flex: 1;
        border: 1.5px solid #e5e7eb;
        border-radius: 22px;
        padding: 10px 16px;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: border-color .2s;
        background: #fff;
    }
    #apmChatInput:focus { border-color: #CD422B; }
    #apmChatSend {
        width: 38px; height: 38px;
        background: linear-gradient(135deg, #CD422B, #a83220);
        border: none; border-radius: 50%;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: transform .2s, box-shadow .2s;
    }
    #apmChatSend:hover { transform: scale(1.1); box-shadow: 0 4px 12px rgba(205,66,43,.4); }

    /* ── Mobile responsive ─────────────────────────── */
    @media (max-width: 460px) {
        #apmChatBox { width: calc(100vw - 32px); right: 0; }
        #apmChatWidget { right: 16px; bottom: 16px; }
    }
</style>

{{-- ===== Widget HTML ===== --}}
<div id="apmChatWidget">

    {{-- Floating Toggle Button --}}
    <button id="apmChatToggle" aria-label="Buka Chatbot APM">
        <div class="pulse-ring"></div>
        {{-- Chat icon --}}
        <svg id="chatIconOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M21 16c0 1.1-.9 2-2 2H7l-4 4V6c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v10z"/>
        </svg>
        {{-- Close icon (hidden by default) --}}
        <svg id="chatIconClose" class="w-6 h-6 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Chat Box --}}
    <div id="apmChatBox" role="dialog" aria-label="APM Assistant Chat">

        {{-- Header --}}
        <div id="apmChatHeader">
            <div class="bot-avatar">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 001.357 2.059l.096.04A23.87 23.87 0 0021 13.5"/>
                </svg>
            </div>
            <div class="bot-info">
                <div class="bot-name">APM Assistant</div>
                <div class="bot-status">
                    <span class="status-dot"></span>
                    Online · Siap membantu
                </div>
            </div>
            <button onclick="closeChatbot()" class="text-white/70 hover:text-white transition p-1" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div id="apmChatMessages" aria-live="polite"></div>

        {{-- Input --}}
        <div id="apmChatInputArea">
            <input type="text" id="apmChatInput"
                   placeholder="Ketik pertanyaan Anda..."
                   maxlength="300"
                   autocomplete="off"
                   aria-label="Pesan chatbot">
            <button id="apmChatSend" aria-label="Kirim pesan">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var CHATBOT_URL  = '{{ route("chatbot.reply") }}';
    var CSRF_TOKEN   = '{{ csrf_token() }}';
    var isOpen       = false;
    var isTyping     = false;
    var hasGreeted   = false;

    var toggle   = document.getElementById('apmChatToggle');
    var box      = document.getElementById('apmChatBox');
    var messages = document.getElementById('apmChatMessages');
    var input    = document.getElementById('apmChatInput');
    var sendBtn  = document.getElementById('apmChatSend');
    var iconOpen = document.getElementById('chatIconOpen');
    var iconClose= document.getElementById('chatIconClose');

    // ── Toggle open/close ─────────────────────────────────
    toggle.addEventListener('click', function () {
        isOpen ? closeChatbot() : openChatbot();
    });
    window.closeChatbot = function () {
        isOpen = false;
        box.classList.remove('open');
        iconOpen.classList.remove('hidden');
        iconClose.classList.add('hidden');
    };
    function openChatbot() {
        isOpen = true;
        box.classList.add('open');
        iconOpen.classList.add('hidden');
        iconClose.classList.remove('hidden');
        if (!hasGreeted) {
            hasGreeted = true;
            fetchFlow('welcome');
        }
        setTimeout(function () { input.focus(); }, 350);
    }

    // ── Send message ──────────────────────────────────────
    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    });

    function sendMessage() {
        var text = input.value.trim();
        if (!text || isTyping) return;
        input.value = '';
        appendBubble('user', text);
        fetchBot(text, null);
    }

    // ── Quick-reply button clicked ────────────────────────
    function onQuickReply(btn) {
        var flow = btn.dataset.flow;
        var link = btn.dataset.link;
        var ext  = btn.dataset.external === 'true';
        appendBubble('user', btn.textContent.trim());
        if (link) {
            if (ext) { window.open(link, '_blank'); }
            else     { window.location.href = link; }
            return;
        }
        if (flow) { fetchFlow(flow); }
    }

    // ── Fetch by flow id ──────────────────────────────────
    function fetchFlow(flowId) {
        showTyping();
        apiFetch({ flow: flowId });
    }

    // ── Fetch by free text ────────────────────────────────
    function fetchBot(text) {
        showTyping();
        apiFetch({ message: text });
    }

    // ── API call ──────────────────────────────────────────
    function apiFetch(body) {
        isTyping = true;
        fetch(CHATBOT_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify(body)
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            removeTyping();
            isTyping = false;
            if (data.message) appendBubble('bot', data.message);
            if (data.buttons && data.buttons.length) appendButtons(data.buttons);
            scrollBottom();
        })
        .catch(function () {
            removeTyping();
            isTyping = false;
            appendBubble('bot', '😕 Koneksi bermasalah. Silakan coba lagi.');
            scrollBottom();
        });
    }

    // ── Render helpers ────────────────────────────────────
    function appendBubble(who, text) {
        var wrap = document.createElement('div');
        wrap.className = 'chat-bubble-wrap ' + who;

        var bubble = document.createElement('div');
        bubble.className = 'chat-bubble ' + who;
        // Parse simple **bold** markdown
        bubble.innerHTML = escapeHtml(text)
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

        wrap.appendChild(bubble);
        messages.appendChild(wrap);
        scrollBottom();
    }

    function appendButtons(buttons) {
        var group = document.createElement('div');
        group.className = 'chat-btn-group';

        buttons.forEach(function (b) {
            var btn = document.createElement('button');
            btn.className = 'chat-qr-btn' + (b.link ? ' link-btn' : '');
            btn.textContent = b.label;
            if (b.flow)     btn.dataset.flow     = b.flow;
            if (b.link)     btn.dataset.link     = b.link;
            if (b.external) btn.dataset.external = 'true';

            // Add arrow icon for links
            if (b.link) {
                var icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                icon.setAttribute('class', 'w-3 h-3 ml-auto flex-shrink-0');
                icon.setAttribute('fill', 'none');
                icon.setAttribute('viewBox', '0 0 24 24');
                icon.setAttribute('stroke', 'currentColor');
                var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                path.setAttribute('stroke-width', '2');
                path.setAttribute('d', 'M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14');
                icon.appendChild(path);
                btn.appendChild(icon);
            }

            btn.addEventListener('click', function () { onQuickReply(btn); });
            group.appendChild(btn);
        });

        messages.appendChild(group);
    }

    function showTyping() {
        var wrap = document.createElement('div');
        wrap.className = 'chat-bubble-wrap bot';
        wrap.id = 'apmTypingBubble';
        var bubble = document.createElement('div');
        bubble.className = 'chat-bubble bot';
        bubble.innerHTML = '<div class="typing-dots"><span></span><span></span><span></span></div>';
        wrap.appendChild(bubble);
        messages.appendChild(wrap);
        scrollBottom();
    }
    function removeTyping() {
        var el = document.getElementById('apmTypingBubble');
        if (el) el.remove();
    }

    function scrollBottom() {
        setTimeout(function () { messages.scrollTop = messages.scrollHeight; }, 60);
    }

    function escapeHtml(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
})();
</script>
