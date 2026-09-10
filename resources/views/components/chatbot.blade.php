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
        <span class="cb-faq-trigger-label"></span>
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

        <!-- Quick Action Chips (Above Typing Input) -->
        <div class="cb-faq-chips-bar" id="cb-faq-chips">
            @php
                $quickFaqs = $chatbotFaqs
                    ->filter(fn ($f) => $f->quick_question)
                    ->sortBy(fn ($f) => $f->quick_question_order ?? 999)
                    ->values();
            @endphp
            @if ($quickFaqs->isNotEmpty())
                @foreach ($quickFaqs as $faq)
                    <button type="button" class="cb-chip-item" data-topic="db-{{ $faq->id }}">{{ $faq->pertanyaan }}</button>
                @endforeach
            @else
                <button type="button" class="cb-chip-item" data-topic="checkout">Cara Order</button>
                <button type="button" class="cb-chip-item" data-topic="pembayaran">Metode Bayar</button>
                <button type="button" class="cb-chip-item" data-topic="tracking">Lacak Paket</button>
                <button type="button" class="cb-chip-item" data-topic="retur">Syarat Retur</button>
                <button type="button" class="cb-chip-item" data-topic="cs">Hubungi CS</button>
            @endif
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

/* Option Buttons Group Inside First Chat Bubble */
.cb-bubble-options {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 12px;
}

.cb-option-btn {
    width: 100%;
    padding: 9px 12px;
    font-size: 12.5px;
    font-weight: 500;
    font-family: inherit;
    line-height: 1.4;
    color: var(--ink, #11100E);
    background: #FFFFFF;
    border: 1px solid var(--line, #E6E3DE);
    border-radius: 8px;
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

/* Quick Topic Chips (Above Typing Input) */
.cb-faq-chips-bar {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: var(--bg, #FAFAF8);
    border-top: 1px solid var(--line-soft, #F0EDE8);
    overflow-x: auto;
    white-space: nowrap;
}

.cb-chip-item {
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 500;
    color: var(--ink, #11100E);
    background: var(--bg-card, #FFFFFF);
    border: 1px solid var(--line, #E6E3DE);
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.cb-chip-item:hover {
    background: var(--ink, #11100E);
    color: #FFFFFF;
    border-color: var(--ink, #11100E);
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

/* CS Form Card & Input Field Enhancements */
.cb-cs-card {
    white-space: normal !important;
    background: #FFFFFF;
    border: 1px solid var(--line, #E6E3DE);
    border-radius: 12px;
    padding: 14px 16px;
    margin-top: 4px;
    box-shadow: 0 4px 14px rgba(17, 16, 14, 0.05);
    box-sizing: border-box;
    width: 100%;
}

.cb-cs-card * {
    white-space: normal !important;
    box-sizing: border-box;
}

.cb-cs-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px dashed var(--line, #E6E3DE);
}

.cb-cs-icon-badge {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, #25D366, #1EBE57);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(37, 211, 102, 0.3);
}

.cb-cs-title {
    font-weight: 700;
    font-size: 13.5px;
    color: var(--ink, #11100E);
    line-height: 1.2;
}

.cb-cs-subtitle {
    font-size: 11px;
    color: var(--ink-muted, #666);
    margin-top: 2px;
}

.cb-cs-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
}

.cb-cs-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
    text-align: left;
}

.cb-cs-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--ink, #11100E);
    letter-spacing: 0.01em;
}

.cb-cs-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.cb-cs-input-icon {
    position: absolute;
    left: 10px;
    color: #8A8580;
    pointer-events: none;
    transition: color 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cb-cs-input,
.cb-cs-textarea {
    width: 100%;
    padding: 8px 10px 8px 32px;
    font-size: 12.5px;
    font-family: inherit;
    color: var(--ink, #11100E);
    background: var(--bg, #FAFAF8);
    border: 1px solid var(--line, #E6E3DE);
    border-radius: 8px;
    outline: none;
    transition: all 0.2s ease;
}

.cb-cs-textarea {
    padding: 8px 10px 8px 32px;
    resize: vertical;
    min-height: 54px;
    line-height: 1.4;
}

.cb-cs-input:focus,
.cb-cs-textarea:focus {
    background: #FFFFFF;
    border-color: var(--accent, #B8860B);
    box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.12);
}

.cb-cs-input-wrap:focus-within .cb-cs-input-icon {
    color: var(--accent, #B8860B);
}

.cb-cs-submit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 6px;
    padding: 10px 16px;
    font-size: 12.5px;
    font-weight: 600;
    color: #FFFFFF;
    background: linear-gradient(135deg, #25D366 0%, #1EBE57 100%);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    width: 100%;
}

.cb-cs-submit-btn:hover {
    background: linear-gradient(135deg, #1EBE57 0%, #17A34A 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 211, 102, 0.4);
}

.cb-cs-submit-btn:active {
    transform: translateY(0);
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
            id: 'what',
            title: 'Tentang CHRISBALE',
            keywords: ['apa', 'apakah', 'tentang', 'produk', 'sepatu', 'sandal', 'footwear', 'jenis', 'bahan', 'material', 'deskripsi', 'apa itu chrisbale', 'apa yang dijual'],
            question: 'Apa itu CHRISBALE dan produk apa saja yang dijual?',
            answer: `CHRISBALE adalah brand alas kaki (footwear) lokal Indonesia yang menghadirkan koleksi sepatu dan sandal pria berdesain modern, elegan, dan nyaman.

Produk unggulan kami meliputi:
- Sepatu Sneaker & Casual Urban
- Sepatu Pantofel / Formal / Loafers
- Sandal Slip-on & Casual (Model kekinian / Birkenstyle)
Semua produk dibuat dari material sintetis & kulit pilihan berkualitas tinggi yang awet, tidak licin, dan nyaman untuk penggunaan harian.`
        },
        {
            id: 'who',
            title: 'Profil Brand & Target Konsumen',
            keywords: ['siapa', 'siapakah', 'owner', 'produsen', 'pembuat', 'brand', 'lokal', 'siapa chrisbale', 'untuk siapa', 'siapa pemilik'],
            question: 'Siapa CHRISBALE dan untuk siapa produk ini dibuat?',
            answer: `CHRISBALE adalah brand fashion footwear lokal buatan karya anak bangsa Indonesia.

Produk CHRISBALE dirancang khusus untuk pria modern yang mengutamakan penampilan stylish, kerapihan, dan kenyamanan — baik untuk aktivitas kerja/kantor, acara formal, perkuliahan, hingga santai akhir pekan.`
        },
        {
            id: 'where',
            title: 'Lokasi Toko & Pengiriman',
            keywords: ['dimana', 'di mana', 'toko', 'lokasi', 'shopee', 'cb_officialshop', 'alamat', 'gudang', 'dikirim dari', 'offline', 'online', 'dimana tokonya', 'dimana shopee'],
            question: 'Di mana toko resmi CHRISBALE dan dari mana produk dikirim?',
            answer: `Saat ini CHRISBALE berfokus pada penjualan online resmi melalui:
- Website Resmi: CHRISBALE Official
- Shopee Official Store: cb_officialshop (Christian Bale / CHRISBALE Official)

Semua pesanan dikirim langsung dari gudang utama kami di Indonesia dengan pengemasan rapi dan aman menggunakan dus/box resmi.`
        },
        {
            id: 'when',
            title: 'Waktu Operasional, Pengiriman & Garansi',
            keywords: ['kapan', 'jam', 'buka', 'operasional', 'durasi', 'lama', 'waktu', 'sampai', 'garansi', 'proses', 'kapan dikirim', 'berapa hari', 'kapan sampai'],
            question: 'Kapan jam operasional CS, durasi pengiriman, dan batas garansi retur?',
            answer: `Informasi waktu & operasional CHRISBALE:

- Jam Operasional CS: Senin - Minggu (09:00 - 21:00 WIB)
- Proses Pengiriman: Pesanan diproses & dikirim H+1 kerja setelah pembayaran terverifikasi.
- Durasi Pengiriman: 1-3 hari kerja (Jabodetabek / Pulau Jawa) & 3-5 hari kerja (Luar Pulau Jawa).
- Batas Garansi Retur / Tukar Size: Maksimal 7 hari setelah paket diterima.`
        },
        {
            id: 'why',
            title: 'Keunggulan Produk',
            keywords: ['mengapa', 'kenapa', 'keunggulan', 'kelebihan', 'alasan', 'kenapa harus', 'mengapa memilih', 'bagus', 'kualitas', 'kenapa beli'],
            question: 'Mengapa saya harus memilih & membeli produk CHRISBALE?',
            answer: `Keunggulan utama membeli produk CHRISBALE:

1. Desain Elegan & Ergonomis: Mengikuti tren fashion modern dengan kenyamanan maksimal.
2. Material Berkualitas Tinggi: Awet, fleksibel, jahitan rapi, dan sol anti-licin.
3. Harga Terjangkau: Kualitas premium dengan harga brand lokal yang bersahabat.
4. Garansi Tukar Size: Jika ukuran kurang pas, bisa ditukar dalam 7 hari.
5. 100% Produk Original & Layanan CS Responsif.`
        },
        {
            id: 'checkout',
            title: 'Panduan Checkout',
            keywords: ['checkout', 'beli', 'cara pesan', 'order', 'pesan', 'keranjang', 'langkah checkout', 'bagaimana beli', 'bagaimana pesan'],
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
            keywords: ['bayar', 'pembayaran', 'transfer', 'bank', 'cod', 'e-wallet', 'gopay', 'ovo', 'dana', 'shopeepay', 'va', 'virtual account', 'kartu kredit', 'bagaimana bayar'],
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
            keywords: ['track', 'tracking', 'lacak', 'status', 'resi', 'posisi', 'dikirim', 'dimana paket', 'pengiriman', 'paket', 'bagaimana lacak'],
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
            keywords: ['retur', 'tukar', 'kembalikan', 'garansi', 'ukuran', 'size', 'rusak', 'batal', 'pengembalian', 'salah size', 'bagaimana retur'],
            question: 'Bagaimana kebijakan & cara melakukan retur produk?',
            answer: `Ketentuan & Langkah Pengajuan Retur / Tukar Size:

1. Pengajuan retur/tukar size maksimal 7 hari setelah pesanan diterima.
2. Sepatu belum pernah dipakai outdoor & box asli dalam kondisi utuh.
3. Wajib menyertakan video unboxing sebagai syarat verifikasi.
4. Hubungi Customer Service kami untuk bantuan proses retur.`
        },
        {
            id: 'cs',
            title: 'Customer Service & Bantuan',
            keywords: ['cs', 'contact', 'admin', 'bantuan', 'hubungi cs', 'whatsapp', 'email', 'telepon', 'kontak', 'hubungi admin'],
            question: 'Bagaimana cara menghubungi Customer Service?',
            answer: `Layanan Customer Service CHRISBALE siap membantu Anda:

- WhatsApp CS: 0812-3456-7890
- Email: support@chrisbale.com
- Jam Operasional: Senin - Minggu (09:00 - 21:00 WIB)`
        }
    ];

    // -----------------------------------------------------------------
    // Data FAQ dari tabel chatbot_faq (database)
    // - Dipakai untuk matching keyword & pertanyaan.
    // - Jika pertanyaan chatbot_faq sama dengan data manual, pakai data
    //   dari chatbot_faq. Sebaliknya data manual tetap dipertahankan.
    // -----------------------------------------------------------------
    @php
        $dbFaqJson = $chatbotFaqs->map(function ($f) {
            return [
                'id' => 'db-' . $f->id,
                'pertanyaan' => $f->pertanyaan,
                'jawaban' => $f->jawaban,
                'keywords' => is_array($f->keywords) ? $f->keywords : [],
            ];
        })->values();
    @endphp
    const dbFaqList = @json($dbFaqJson);

    dbFaqList.forEach(function (db) {
        var matched = false;
        for (var i = 0; i < faqDataset.length; i++) {
            if (faqDataset[i].question && String(faqDataset[i].question).toLowerCase().trim() === String(db.pertanyaan).toLowerCase().trim()) {
                faqDataset[i].title = db.pertanyaan;
                faqDataset[i].question = db.pertanyaan;
                faqDataset[i].answer = db.jawaban;
                faqDataset[i].keywords = (db.keywords && db.keywords.length) ? db.keywords.concat([db.pertanyaan]) : [db.pertanyaan];
                faqDataset[i].dbId = db.id;
                matched = true;
                break;
            }
        }
        if (!matched) {
            faqDataset.push({
                id: db.id,
                title: db.pertanyaan,
                question: db.pertanyaan,
                answer: db.jawaban,
                keywords: (db.keywords && db.keywords.length) ? db.keywords.concat([db.pertanyaan]) : [db.pertanyaan]
            });
        }
    });

    const fallbackAnswer = "Maaf, pertanyaan Anda belum ada di data template kami. Silakan pilih dari opsi pertanyaan yang tersedia di bawah ini atau hubungi Customer Service kami untuk bantuan lebih lanjut.";

    // DOM Elements
    const triggerBtn = document.getElementById('cb-faq-trigger');
    const modal = document.getElementById('cb-faq-modal');
    const closeBtn = document.getElementById('cb-faq-close-btn');
    const resetBtn = document.getElementById('cb-faq-reset-btn');
    const chatBody = document.getElementById('cb-faq-body');
    const form = document.getElementById('cb-faq-form');
    const inputField = document.getElementById('cb-faq-input');
    const chipsContainer = document.getElementById('cb-faq-chips');
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

    // Render Initial Bot Greeting with Option Buttons inside the First Chat Bubble
    function initChat() {
        chatBody.innerHTML = '';
        const welcomeDiv = document.createElement('div');
        welcomeDiv.className = 'cb-msg cb-msg-bot';
        welcomeDiv.innerHTML = `
            <div class="cb-msg-bubble">
                Halo! Selamat datang di <strong>CHRISBALE</strong>.<br><br>
                Silakan pilih topik pertanyaan di bawah ini atau ketik pertanyaan Anda secara langsung:
                <div class="cb-bubble-options">
                    <button type="button" class="cb-option-btn" data-topic="checkout">Panduan Checkout</button>
                    <button type="button" class="cb-option-btn" data-topic="pembayaran">Panduan Pembayaran</button>
                    <button type="button" class="cb-option-btn" data-topic="cs">Hubungi CS</button>
                </div>
            </div>
            <div class="cb-msg-time">${getCurrentTime()}</div>
        `;
        chatBody.appendChild(welcomeDiv);
        scrollToBottom();
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

    // Render CS Contact Form inside Chat
    function appendCsFormMessage() {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'cb-msg cb-msg-bot';
        msgDiv.style.maxWidth = '96%';
        msgDiv.innerHTML = `
            <div class="cb-msg-bubble" style="padding:4px; background:transparent; border:none;">
                <div class="cb-cs-card">
                    <div class="cb-cs-header">
                        <div class="cb-cs-icon-badge">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="cb-cs-title">Hubungi Customer Service</div>
                            <div class="cb-cs-subtitle">Lengkapi data untuk terhubung via WhatsApp</div>
                        </div>
                    </div>
                    <form class="cb-cs-form" autocomplete="off">
                        <div class="cb-cs-field">
                            <label class="cb-cs-label">Nama Lengkap</label>
                            <div class="cb-cs-input-wrap">
                                <span class="cb-cs-input-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <input type="text" name="cs_name" class="cb-cs-input" placeholder="Masukkan nama Anda" required />
                            </div>
                        </div>
                        <div class="cb-cs-field">
                            <label class="cb-cs-label">Alamat Email</label>
                            <div class="cb-cs-input-wrap">
                                <span class="cb-cs-input-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <input type="email" name="cs_email" class="cb-cs-input" placeholder="nama@email.com" required />
                            </div>
                        </div>
                        <div class="cb-cs-field">
                            <label class="cb-cs-label">Keperluan / Subject</label>
                            <div class="cb-cs-input-wrap">
                                <span class="cb-cs-input-icon" style="top:10px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </span>
                                <textarea name="cs_subject" class="cb-cs-textarea" rows="2" placeholder="Tuliskan keperluan / pertanyaan..." required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="cb-cs-submit-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            Hubungi via WhatsApp
                        </button>
                    </form>
                </div>
            </div>
            <div class="cb-msg-time">${getCurrentTime()}</div>
        `;
        chatBody.appendChild(msgDiv);
        scrollToBottom();
    }

    function appendOptionButtons() {
        const optionsDiv = document.createElement('div');
        optionsDiv.className = 'cb-msg cb-msg-bot';
        optionsDiv.innerHTML = `
            <div class="cb-msg-bubble">
                Silakan pilih dari opsi pertanyaan berikut:
                <div class="cb-bubble-options">
                    <button type="button" class="cb-option-btn" data-topic="checkout">Panduan Checkout</button>
                    <button type="button" class="cb-option-btn" data-topic="pembayaran">Panduan Pembayaran</button>
                    <button type="button" class="cb-option-btn" data-topic="tracking">Panduan Tracking Pesanan</button>
                    <button type="button" class="cb-option-btn" data-topic="retur">Panduan Retur Produk</button>
                    <button type="button" class="cb-option-btn" data-topic="cs">Hubungi CS</button>
                </div>
            </div>
            <div class="cb-msg-time">${getCurrentTime()}</div>
        `;
        chatBody.appendChild(optionsDiv);
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
        const item = faqDataset.find(d => d.id === topicId || d.dbId === topicId);
        if (!item) return;

        appendUserMessage(item.question);
        showTypingIndicator();

        setTimeout(function () {
            removeTypingIndicator();
            if (topicId === 'cs') {
                appendCsFormMessage();
            } else {
                appendBotMessage(item.answer);
            }
        }, 400);
    }

    // =========================================================
    // FUZZY MATCHING (LEVENSHTEIN DISTANCE) & TYPO NORMALIZATION
    // =========================================================
    function levenshteinDistance(a, b) {
        if (a.length === 0) return b.length;
        if (b.length === 0) return a.length;
        const matrix = [];
        for (let i = 0; i <= b.length; i++) matrix[i] = [i];
        for (let j = 0; j <= a.length; j++) matrix[0][j] = j;

        for (let i = 1; i <= b.length; i++) {
            for (let j = 1; j <= a.length; j++) {
                if (b.charAt(i - 1) === a.charAt(j - 1)) {
                    matrix[i][j] = matrix[i - 1][j - 1];
                } else {
                    matrix[i][j] = Math.min(
                        matrix[i - 1][j - 1] + 1, // substitution
                        matrix[i][j - 1] + 1,     // insertion
                        matrix[i - 1][j] + 1      // deletion
                    );
                }
            }
        }
        return matrix[b.length][a.length];
    }

    function isFuzzyMatch(token, keyword) {
        const t = token.toLowerCase();
        const k = keyword.toLowerCase();
        if (t === k) return true;
        if (t.includes(k) || k.includes(t)) return true;
        
        const minLen = Math.min(t.length, k.length);
        if (minLen <= 3) return t === k;
        
        const maxEdits = minLen <= 5 ? 1 : 2;
        return levenshteinDistance(t, k) <= maxEdits;
    }

    // Common Indonesian Typo & Slang Normalization Dictionary
    const typoDictionary = {
        'cekot': 'checkout', 'cekout': 'checkout', 'chekout': 'checkout', 'orderan': 'order',
        'bhayar': 'bayar', 'biyer': 'bayar', 'byr': 'bayar', 'trf': 'transfer', 'transfr': 'transfer',
        'traking': 'tracking', 'trakin': 'tracking', 'resi': 'tracking', 'resii': 'tracking',
        'ratur': 'retur', 'ritur': 'retur', 'tukar': 'retur', 'garansi': 'retur',
        'crisbal': 'chrisbale', 'krisbale': 'chrisbale', 'krisbal': 'chrisbale', 'shope': 'shopee',
        'dimna': 'dimana', 'dmana': 'dimana', 'gimana': 'bagaimana', 'gmna': 'bagaimana'
    };

    function normalizeTokens(tokens) {
        return tokens.map(token => typoDictionary[token] || token);
    }

    // Enhanced Token Scoring Search Engine for 5W1H & Natural Indonesian Input (with Typo Tolerance)
    function handleManualQuestion(userQuery) {
        const trimmed = userQuery.trim();
        if (!trimmed) return;

        appendUserMessage(trimmed);
        inputField.value = '';
        showTypingIndicator();

        const queryLower = trimmed.toLowerCase();
        const cleanQuery = queryLower.replace(/[^\w\s]/gi, ' ');
        const rawTokens = cleanQuery.split(/\s+/).filter(t => t.length > 1);
        const tokens = normalizeTokens(rawTokens);

        // Unsupported or out-of-scope topics check
        const unsupportedKeywords = [
            'kripto', 'crypto', 'bitcoin', 'eth', 'usdt', 'paypal', 'pinjol', 
            'kredit hp', 'paylater', 'utang', 'hutang', 'diskon ultah', 'ulang tahun'
        ];
        
        const isUnsupported = unsupportedKeywords.some(ukw => queryLower.includes(ukw));

        let bestMatch = null;
        let highestScore = 0;

        if (!isUnsupported) {
            faqDataset.forEach(item => {
                let score = 0;

                // 1. Multi-word phrase exact matches
                item.keywords.forEach(kw => {
                    const kwLower = kw.toLowerCase();
                    if (kwLower.includes(' ') && queryLower.includes(kwLower)) {
                        score += 15;
                    } else if (queryLower.includes(kwLower)) {
                        score += 6;
                    }
                });

                // 2. Token overlap & Fuzzy Typo Matches
                tokens.forEach(token => {
                    item.keywords.forEach(kw => {
                        const kwLower = kw.toLowerCase();
                        if (kwLower === token) {
                            score += 5;
                        } else if (isFuzzyMatch(token, kwLower)) {
                            score += 4; // Bonus score for typo fuzzy match
                        }
                    });
                });

                if (score > highestScore) {
                    highestScore = score;
                    bestMatch = item;
                }
            });
        }

        setTimeout(function () {
            removeTypingIndicator();
            if (bestMatch && highestScore >= 4 && !isUnsupported) {
                if (bestMatch.id === 'cs') {
                    appendCsFormMessage();
                } else {
                    appendBotMessage(`**${bestMatch.title}**:\n\n${bestMatch.answer}`);
                }
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

    // Handle Chips clicks above typing bar
    if (chipsContainer) {
        chipsContainer.addEventListener('click', function (e) {
            const btn = e.target.closest('.cb-chip-item');
            if (btn) {
                const topic = btn.getAttribute('data-topic');
                handleSelectTopic(topic);
            }
        });
    }

    // Handle CS Form submission inside chatbot (Event Delegation)
    chatBody.addEventListener('submit', function (e) {
        const csForm = e.target.closest('.cb-cs-form');
        if (csForm) {
            e.preventDefault();
            const nameInput = csForm.querySelector('input[name="cs_name"]');
            const emailInput = csForm.querySelector('input[name="cs_email"]');
            const subjectInput = csForm.querySelector('textarea[name="cs_subject"]');

            const name = nameInput ? nameInput.value.trim() : '';
            const email = emailInput ? emailInput.value.trim() : '';
            const subject = subjectInput ? subjectInput.value.trim() : '';

            if (!name || !email || !subject) return;

            // WhatsApp API Phone Number
            const WA_PHONE = '6287774487198';

            const messageText = `Halo CS CHRISBALE,\n\nNama: ${name}\nEmail: ${email}\nKeperluan: ${subject}`;
            const encodedText = encodeURIComponent(messageText);

            const waUrl = WA_PHONE 
                ? `https://wa.me/${WA_PHONE}?text=${encodedText}`
                : `https://wa.me/?text=${encodedText}`;

            window.open(waUrl, '_blank');

            appendBotMessage(`Terima kasih **${escapeHTML(name)}**! Data Anda telah disiapkan. Mengalihkan Anda ke WhatsApp Customer Service...`);
        }
    });

    // Form submit for manual input
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        handleManualQuestion(inputField.value);
    });

    // Global helper to open FAQ chatbot from anywhere
    window.openFaqChatbot = function (topic) {
        if (!isModalOpen) {
            toggleModal();
        }
        if (topic) {
            handleSelectTopic(topic);
        }
    };

    // Initialize initial chat messages
    initChat();
});
</script>
