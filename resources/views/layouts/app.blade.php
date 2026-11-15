<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'OdeonMoney' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="app-body {{ ($admin ?? false) ? 'admin-mode' : '' }}">
    <div class="app-shell">
        <button class="mobile-menu-toggle" type="button" aria-label="Buka navigasi" aria-expanded="false" data-sidebar-toggle>☰</button>
        <div class="sidebar-backdrop" data-sidebar-backdrop></div>
        @include('partials.sidebar')
        <main class="main-area">
            <img class="app-background" src="{{ asset('assets/vihara.jpg') }}" alt="" aria-hidden="true">
            @include('partials.topbar')
            <div class="page-content">
                @if(session('success'))
                    <div class="flash success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="flash error">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
<script>
        document.addEventListener('click', function(e) {
            document.querySelectorAll('.dropdown-details').forEach(function(dropdown) {
                if (!dropdown.contains(e.target)) {
                    dropdown.removeAttribute('open');
                }
            });
        });
    </script>

    {{-- AI Chatbot Floating Button --}}
<div id="ai-chat-widget">

    <button type="button" id="ai-chat-toggle" aria-label="Buka AI Assistant">
        <span>✦</span>
    </button>

    <div id="ai-chat-panel">
        <div class="ai-chat-header">
            <div>
                <strong>✦ AI Financial Assistant</strong>
                <small>Asisten keuangan Si Kawan Odeon</small>
            </div>

            <button type="button" id="ai-chat-close">&times;</button>
        </div>

        <div class="ai-chat-body" id="ai-chat-body">
            <div class="ai-message">
                Halo! Saya AI Financial Assistant Si Kawan Odeon.
                Saya siap membantu menganalisis kondisi keuangan.
                <br><br>
                Apa yang ingin kamu ketahui?
            </div>

            <div class="ai-chat-suggestions">
                <button type="button" class="ai-suggestion">
                    Berapa total pemasukan dan pengeluaran bulan ini?
                </button>

                <button type="button" class="ai-suggestion">
                    Tampilkan sumber pemasukan terbesar
                </button>

                <button type="button" class="ai-suggestion">
                    Apa pengeluaran terbesar bulan ini?
                </button>

                <button type="button" class="ai-suggestion">
                    Bandingkan keuangan bulan ini dan sebelumnya
                </button>

                <button type="button" class="ai-suggestion">
                    Buatkan ringkasan laporan keuangan
                </button>
            </div>
        </div>

        <form id="ai-chat-form" class="ai-chat-input">
            <input
                type="text"
                id="ai-chat-message"
                placeholder="Tanyakan sesuatu..."
                autocomplete="off"
            >
            <button type="submit">➤</button>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('ai-chat-toggle');
    const panel = document.getElementById('ai-chat-panel');
    const close = document.getElementById('ai-chat-close');
    const form = document.getElementById('ai-chat-form');
    const input = document.getElementById('ai-chat-message');
    const chatBody = document.getElementById('ai-chat-body');
    const submitButton = form.querySelector('button');

    if (!toggle || !panel || !close || !form || !input || !chatBody) {
        return;
    }

    toggle.addEventListener('click', function () {
        panel.classList.toggle('active');
    });

    close.addEventListener('click', function () {
        panel.classList.remove('active');
    });

    async function sendMessage(message) {
        message = message.trim();

        if (!message) return;

        // Tampilkan pesan pengguna
        const userMessage = document.createElement('div');
        userMessage.className = 'ai-user-message';
        userMessage.textContent = message;
        chatBody.appendChild(userMessage);

        input.value = '';
        input.disabled = true;
        submitButton.disabled = true;

        // Tampilkan indikator proses
        const loading = document.createElement('div');
        loading.className = 'ai-message';
        loading.textContent = 'Sedang menganalisis data keuangan...';
        chatBody.appendChild(loading);

        chatBody.scrollTop = chatBody.scrollHeight;

        try {
            const response = await fetch(
                "{{ route('ai-assistant.chat') }}",
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                }
            );

            const data = await response.json();

            loading.remove();

            const reply = document.createElement('div');
            reply.className = 'ai-message';

            if (!response.ok) {
                reply.textContent = data.message ||
                    'Maaf, terjadi kesalahan saat memproses pertanyaan.';
            } else {
                reply.textContent = data.reply ||
                    'Maaf, belum ada jawaban yang tersedia.';
            }

            chatBody.appendChild(reply);

        } catch (error) {
            loading.remove();

            const reply = document.createElement('div');
            reply.className = 'ai-message';
            reply.textContent =
                'Tidak dapat terhubung ke server. Silakan coba lagi.';

            chatBody.appendChild(reply);

        } finally {
            input.disabled = false;
            submitButton.disabled = false;
            input.focus();
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    }

    // Kirim pesan melalui tombol
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        sendMessage(input.value);
    });

    // Klik pertanyaan rekomendasi langsung mengirim pesan
    document.querySelectorAll('.ai-suggestion').forEach(function (button) {
        button.addEventListener('click', function () {
            sendMessage(this.textContent.trim());
        });
    });
});
</script>

</body>
</html>
