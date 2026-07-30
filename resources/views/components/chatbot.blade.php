<!-- =========================================================
     CHRISBALE STICKY FAQ CHATBOT COMPONENT (CLEAN DESIGN)
     ========================================================= -->
<div id="cb-faq-wrapper">
    <!-- Sticky Floating Button (Bottom Right) -->
    <button id="cb-faq-trigger" class="cb-faq-trigger-btn" aria-label="Buka Chat FAQ CHRISBALE">
        <div class="cb-faq-trigger-icon">
            <svg class="cb-icon-chat" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <svg class="cb-icon-close" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>
        <span class="cb-faq-trigger-label">Tanya FAQ</span>
        <span class="cb-faq-pulse-dot"></span>
    </button>

    <!-- Popup Side Modal (Right Side) -->
    <div id="cb-faq-modal" class="cb-faq-modal" aria-hidden="true">
        <!-- Modal Header -->
        <div class="cb-faq-header">
            <div class="cb-faq-header-brand">
                <div class="cb-faq-avatar">
                    <span>CB</span>
                </div>
                <div class="cb-faq-header-meta">
                    <h3 class="cb-faq-title">CHRISBALE Assistant</h3>
                    <p class="cb-faq-status"><span class="cb-status-indicator"></span> Online &bull; FAQ Otomatis</p>
                </div>
            </div>
            <div class="cb-faq-header-actions">
                <button type="button" id="cb-faq-reset-btn" class="cb-faq-action-btn" title="Reset Percakapan">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                </button>
                <button type="button" id="cb-faq-close-btn" class="cb-faq-action-btn" title="Tutup Modal">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat History Body -->
        <div id="cb-faq-body" class="cb-faq-body">
            <!-- Messages and option buttons injected dynamically via Javascript -->
        </div>

        <!-- Footer Manual Input -->
        <form id="cb-faq-form" class="cb-faq-footer" autocomplete="off" onsubmit="return false;">
            <input type="text" id="cb-faq-input" class="cb-faq-input-field" placeholder="Ketik pertanyaan Anda di sini..." />
            <button type="submit" id="cb-faq-send-btn" class="cb-faq-send-btn" title="Kirim Pertanyaan">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>
</div>

<style>
/* =========================================================
   CHRISBALE STICKY CHATBOT FAQ STYLES
   ========================================================= */
#cb-faq-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    position: relative;
    z-index: 999999;
}

/* Sticky Trigger Button (Bottom Right) */
.cb-faq-trigger-btn {
    position: fixed;
    bottom: 28px;
    right: 28px;
    height: 52px;
    padding: 0 20px 0 16px;
    background: var(--ink, #11100E);
    color: #FFFFFF;
    border: 1.5px solid var(--accent, #B8860B);
    border-radius: 30px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(17, 16, 14, 0.25);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    z-index: 999999;
}

.cb-faq-trigger-btn:hover {
    transform: translateY(-3px) scale(1.02);
    background: #000000;
    box-shadow: 0 12px 30px rgba(184, 134, 11, 0.35);
    border-color: var(--accent-light, #D4A843);
}

.cb-faq-trigger-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-light, #D4A843);
}

.cb-faq-trigger-label {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.02em;
    color: #FFFFFF;
}

.cb-faq-pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #2ECC71;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
    animation: cbPulse 2s infinite;
}

@keyframes cbPulse {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
    }
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 6px rgba(46, 204, 113, 0);
    }
    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
    }
}

/* Side Popup Modal Window */
.cb-faq-modal {
    position: fixed;
    bottom: 92px;
    right: 28px;
    width: 400px;
    max-width: calc(100vw - 36px);
    height: 580px;
    max-height: calc(100vh - 120px);
    background: var(--bg-card, #FFFFFF);
    border: 1px solid var(--line, #E6E3DE);
    border-radius: 16px;
    box-shadow: 0 16px 48px rgba(17, 16, 14, 0.18);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px) scale(0.95);
    transform-origin: bottom right;
    transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
    z-index: 999998;
}

.cb-faq-modal.cb-active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}

/* Header */
.cb-faq-header {
    background: var(--ink, #11100E);
    color: #FFFFFF;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--accent, #B8860B);
}

.cb-faq-header-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cb-faq-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--accent, #B8860B), var(--accent-dark, #8B6508));
    color: #FFFFFF;
    font-weight: 800;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    letter-spacing: 0.05em;
    box-shadow: 0 2px 8px rgba(184, 134, 11, 0.4);
}

.cb-faq-title {
    font-family: 'Playfair Display', serif;
    font-size: 15px;
    font-weight: 700;
    color: #FFFFFF;
    margin: 0;
    line-height: 1.2;
}

.cb-faq-status {
    font-size: 11px;
    color: var(--line-soft, #F0EDE8);
    margin: 2px 0 0 0;
    display: flex;
    align-items: center;
    gap: 5px;
    opacity: 0.85;
}

.cb-status-indicator {
    width: 6px;
    height: 6px;
    background-color: #2ECC71;
    border-radius: 50%;
    display: inline-block;
}

.cb-faq-header-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.cb-faq-action-btn {
    background: transparent;
    border: none;
    color: #CCCCCC;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}

.cb-faq-action-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #FFFFFF;
}

/* Chat Body */
.cb-faq-body {
    flex: 1;
    padding: 18px 16px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 14px;
    background: #FFFFFF;
    scroll-behavior: smooth;
}

/* Scrollbar */
.cb-faq-body::-webkit-scrollbar {
    width: 5px;
    display: block !important;
}

.cb-faq-body::-webkit-scrollbar-track {
    background: transparent;
    display: block !important;
}

.cb-faq-body::-webkit-scrollbar-thumb {
    background: #D1CDC7;
    border-radius: 4px;
    display: block !important;
}

/* Chat Bubbles */
.cb-msg {
    display: flex;
    flex-direction: column;
    max-width: 88%;
    animation: cbFadeMsg 0.25s ease-out forwards;
}

@keyframes cbFadeMsg {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

.cb-msg-bot {
    align-self: flex-start;
}

.cb-msg-user {
    align-self: flex-end;
}

.cb-msg-bubble {
    padding: 12px 15px;
    border-radius: 12px;
    font-size: 13px;
    line-height: 1.5;
    word-break: break-word;
    white-space: pre-line;
}

.cb-msg-bot .cb-msg-bubble {
    background: var(--bg, #FAFAF8);
    color: var(--ink, #11100E);
    border: 1px solid var(--line, #E6E3DE);
    border-top-left-radius: 4px;
}

.cb-msg-user .cb-msg-bubble {
    background: var(--ink, #11100E);
    color: #FFFFFF;
    border-top-right-radius: 4px;
}

.cb-msg-time {
    font-size: 10px;
    color: var(--ink-muted, #8A8580);
    margin-top: 4px;
    padding: 0 4px;
}

.cb-msg-bot .cb-msg-time { align-self: flex-start; }
.cb-msg-user .cb-msg-time { align-self: flex-end; }

/* Option Buttons Group (Matching Chat Bubble Style) */
.cb-faq-options-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 2px;
    margin-bottom: 6px;
    max-width: 88%;
    align-self: flex-start;
    animation: cbFadeMsg 0.25s ease-out forwards;
}

.cb-option-btn {
    width: 100%;
    padding: 11px 14px;
    font-size: 13px;
    font-weight: 500;
    font-family: inherit;
    line-height: 1.4;
    color: var(--ink, #11100E);
    background: var(--bg, #FAFAF8);
    border: 1px solid var(--line, #E6E3DE);
    border-radius: 12px;
    border-top-left-radius: 4px;
    text-align: left;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.cb-option-btn:hover {
    background: var(--ink, #11100E);
    color: #FFFFFF;
    border-color: var(--ink, #11100E);
    transform: translateX(2px);
}

/* Typing Indicator */
.cb-typing-indicator {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 10px 14px;
    background: var(--bg, #FAFAF8);
    border: 1px solid var(--line, #E6E3DE);
    border-radius: 12px;
    border-top-left-radius: 4px;
    width: fit-content;
}

.cb-typing-dot {
    width: 6px;
    height: 6px;
    background-color: var(--ink-muted, #8A8580);
    border-radius: 50%;
    animation: cbTyping 1.4s infinite ease-in-out both;
}

.cb-typing-dot:nth-child(1) { animation-delay: -0.32s; }
.cb-typing-dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes cbTyping {
    0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
    40% { transform: scale(1); opacity: 1; }
}

/* Footer Input */
.cb-faq-footer {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: var(--bg-card, #FFFFFF);
    border-top: 1px solid var(--line, #E6E3DE);
}

.cb-faq-input-field {
    flex: 1;
    padding: 10px 14px;
    font-size: 13px;
    font-family: inherit;
    color: var(--ink, #11100E);
    background: var(--bg, #FAFAF8);
    border: 1.5px solid var(--line, #E6E3DE);
    border-radius: 24px;
    transition: border-color 0.2s, background 0.2s;
}

.cb-faq-input-field:focus {
    outline: none;
    border-color: var(--accent, #B8860B);
    background: #FFFFFF;
}

.cb-faq-send-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--ink, #11100E);
    color: var(--accent-light, #D4A843);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s, background 0.2s;
    flex-shrink: 0;
}

.cb-faq-send-btn:hover {
    background: #000000;
    transform: scale(1.06);
    color: #FFFFFF;
}

/* Responsive adjustment for small mobile screens */
@media (max-width: 480px) {
    .cb-faq-trigger-btn {
        bottom: 20px;
        right: 20px;
        padding: 0 16px 0 14px;
        height: 48px;
    }
    .cb-faq-modal {
        right: 16px;
        left: 16px;
        bottom: 78px;
        width: auto;
        max-width: none;
        height: 520px;
    }
}
</style>

<!-- JS Implementation -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // =========================================================
    // FAQ TEMPLATE DATA & KEYWORD MAPPING
    // =========================================================
    const faqDataset = [
        {
            id: 'checkout',
            title: 'Panduan Checkout',
            keywords: ['checkout', 'beli', 'cara pesan', 'order', 'pesan', 'keranjang', 'langkah checkout'],
            question: 'Bagaimana cara melakukan checkout di CHRISBALE?',
            answer: `Berikut adalah panduan langkah mudah untuk melakukan checkout:

1. Pilih produk & ukuran sepatu yang Anda inginkan.
2. Klik tombol "Tambah ke Keranjang" atau "Beli Sekarang".
3. Buka halaman Keranjang / Checkout dan isi alamat pengiriman dengan lengkap.
4. Pilih metode pembayaran & masukkan kode promo / voucher (jika ada).
5. Klik "Bayar Sekarang" untuk menyelesaikan pesanan Anda.`
        },
        {
            id: 'pembayaran',
            title: 'Panduan Pembayaran',
            keywords: ['bayar', 'pembayaran', 'transfer', 'bank', 'cod', 'e-wallet', 'gopay', 'ovo', 'dana', 'shopeepay', 'va', 'virtual account', 'kartu kredit'],
            question: 'Apa saja metode pembayaran yang tersedia?',
            answer: `CHRISBALE menyediakan berbagai pilihan pembayaran yang aman & mudah:

- Transfer Bank / Virtual Account (BCA, Mandiri, BRI, BNI)
- E-Wallet (GoPay, OVO, DANA, ShopeePay)
- Kartu Kredit / Debit (Visa & Mastercard)
- COD (Bayar di Tempat) untuk wilayah yang didukung.`
        },
        {
            id: 'tracking',
            title: 'Panduan Tracking Pesanan',
            keywords: ['track', 'tracking', 'lacak', 'status', 'resi', 'posisi', 'dikirim', 'dimana', 'pengiriman', 'paket'],
            question: 'Bagaimana cara melacak / tracking status pesanan saya?',
            answer: `Untuk melacak status pesanan Anda:

1. Masuk ke Akun CHRISBALE Anda.
2. Buka menu "Dashboard" > "Pesanan Saya".
3. Pilih pesanan yang ingin Anda lacak.
4. Klik tombol "Lacak Pesanan" untuk melihat status pengiriman & nomor resi secara real-time.`
        },
        {
            id: 'retur',
            title: 'Panduan Retur Produk',
            keywords: ['retur', 'tukar', 'kembalikan', 'garansi', 'ukuran', 'size', 'rusak', 'batal', 'pengembalian', 'salah size'],
            question: 'Bagaimana kebijakan & cara melakukan retur produk?',
            answer: `Ketentuan & Langkah Pengajuan Retur / Tukar Size:

1. Pengajuan retur/tukar size maksimal 7 hari setelah pesanan diterima.
2. Sepatu belum pernah dipakai outdoor & box asli dalam kondisi utuh.
3. Wajib menyertakan video unboxing sebagai syarat verifikasi.
4. Hubungi Customer Service kami untuk bantuan proses retur.`
        }
    ];

    const fallbackAnswer = "Maaf, pertanyaan Anda belum ada di data template kami. Silakan pilih dari opsi pertanyaan yang tersedia di bawah ini atau hubungi Customer Service kami untuk bantuan lebih lanjut.";

    // DOM Elements
    const triggerBtn = document.getElementById('cb-faq-trigger');
    const modal = document.getElementById('cb-faq-modal');
    const closeBtn = document.getElementById('cb-faq-close-btn');
    const resetBtn = document.getElementById('cb-faq-reset-btn');
    const chatBody = document.getElementById('cb-faq-body');
    const form = document.getElementById('cb-faq-form');
    const inputField = document.getElementById('cb-faq-input');
    const iconChat = triggerBtn.querySelector('.cb-icon-chat');
    const iconClose = triggerBtn.querySelector('.cb-icon-close');

    let isModalOpen = false;

    // Helper: Get Current Time String (HH:MM)
    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // Toggle Modal Display
    function toggleModal() {
        isModalOpen = !isModalOpen;
        if (isModalOpen) {
            modal.classList.add('cb-active');
            modal.setAttribute('aria-hidden', 'false');
            iconChat.style.display = 'none';
            iconClose.style.display = 'block';
            inputField.focus();
        } else {
            modal.classList.remove('cb-active');
            modal.setAttribute('aria-hidden', 'true');
            iconChat.style.display = 'block';
            iconClose.style.display = 'none';
        }
    }

    // Append Option Buttons directly under chat bubble
    function appendOptionButtons() {
        const optsDiv = document.createElement('div');
        optsDiv.className = 'cb-faq-options-group';
        optsDiv.innerHTML = `
            <button type="button" class="cb-option-btn" data-topic="checkout">Panduan Checkout</button>
            <button type="button" class="cb-option-btn" data-topic="pembayaran">Panduan Pembayaran</button>
            <button type="button" class="cb-option-btn" data-topic="tracking">Panduan Tracking Pesanan</button>
            <button type="button" class="cb-option-btn" data-topic="retur">Panduan Retur Produk</button>
        `;
        chatBody.appendChild(optsDiv);
        scrollToBottom();
    }

    // Render Initial Bot Greeting
    function initChat() {
        chatBody.innerHTML = '';
        appendBotMessage(`Halo! Selamat datang di **CHRISBALE**.

Silakan pilih topik pertanyaan di bawah ini atau ketik pertanyaan Anda secara langsung:`);
        appendOptionButtons();
    }

    // Append Message to Chat Log
    function appendUserMessage(text) {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'cb-msg cb-msg-user';
        msgDiv.innerHTML = `
            <div class="cb-msg-bubble">${escapeHTML(text)}</div>
            <div class="cb-msg-time">${getCurrentTime()}</div>
        `;
        chatBody.appendChild(msgDiv);
        scrollToBottom();
    }

    function appendBotMessage(text) {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'cb-msg cb-msg-bot';
        msgDiv.innerHTML = `
            <div class="cb-msg-bubble">${formatMarkdown(text)}</div>
            <div class="cb-msg-time">${getCurrentTime()}</div>
        `;
        chatBody.appendChild(msgDiv);
        scrollToBottom();
    }

    // Show Typing Dots Effect
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'cb-typing-indicator';
        typingDiv.className = 'cb-typing-indicator';
        typingDiv.innerHTML = `
            <div class="cb-typing-dot"></div>
            <div class="cb-typing-dot"></div>
            <div class="cb-typing-dot"></div>
        `;
        chatBody.appendChild(typingDiv);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        const typingDiv = document.getElementById('cb-typing-indicator');
        if (typingDiv) {
            typingDiv.remove();
        }
    }

    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // HTML Escaping
    function escapeHTML(str) {
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Basic Formatting for bold markdown
    function formatMarkdown(str) {
        let escaped = escapeHTML(str);
        return escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    }

    // Handle Selecting a Template Topic
    function handleSelectTopic(topicId) {
        const item = faqDataset.find(d => d.id === topicId);
        if (!item) return;

        appendUserMessage(item.question);
        showTypingIndicator();

        setTimeout(function () {
            removeTypingIndicator();
            appendBotMessage(item.answer);
        }, 400);
    }

    // Handle Manual Question Typing Search
    function handleManualQuestion(userQuery) {
        const trimmed = userQuery.trim();
        if (!trimmed) return;

        appendUserMessage(trimmed);
        inputField.value = '';
        showTypingIndicator();

        const queryLower = trimmed.toLowerCase();

        // Unsupported or out-of-scope topics check
        const unsupportedKeywords = [
            'kripto', 'crypto', 'bitcoin', 'eth', 'usdt', 'paypal', 'pinjol', 
            'kredit hp', 'paylater', 'utang', 'hutang', 'diskon ultah', 'ulang tahun',
            'toko fisik', 'toko offline', 'lokasi toko', 'cabang'
        ];
        
        const isUnsupported = unsupportedKeywords.some(ukw => queryLower.includes(ukw));

        let matchedItem = null;

        if (!isUnsupported) {
            // 1. First priority: Check multi-word phrase matches (e.g. "cara bayar", "lacak resi", "retur barang")
            matchedItem = faqDataset.find(item => {
                return item.keywords.some(kw => kw.includes(' ') && queryLower.includes(kw));
            });

            // 2. Second priority: Check exact word boundary matches for key terms
            if (!matchedItem) {
                matchedItem = faqDataset.find(item => {
                    return item.keywords.some(kw => {
                        const regex = new RegExp(`\\b${kw}\\b`, 'i');
                        return regex.test(queryLower);
                    });
                });
            }
        }

        setTimeout(function () {
            removeTypingIndicator();
            if (matchedItem && !isUnsupported) {
                appendBotMessage(`**${matchedItem.title}**:\n\n${matchedItem.answer}`);
            } else {
                appendBotMessage(fallbackAnswer);
                appendOptionButtons();
            }
        }, 450);
    }

    // EVENT LISTENERS
    triggerBtn.addEventListener('click', toggleModal);
    closeBtn.addEventListener('click', toggleModal);
    
    resetBtn.addEventListener('click', function () {
        initChat();
    });

    // Handle Option Button clicks inside chat log (Event Delegation)
    chatBody.addEventListener('click', function (e) {
        const btn = e.target.closest('.cb-option-btn');
        if (btn) {
            const topic = btn.getAttribute('data-topic');
            handleSelectTopic(topic);
        }
    });

    // Form submit for manual input
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        handleManualQuestion(inputField.value);
    });

    // Initialize initial chat messages
    initChat();
});
</script>
