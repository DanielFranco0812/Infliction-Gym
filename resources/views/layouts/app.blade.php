<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#111111] text-white">
        <div class="min-h-screen bg-[#111111] text-white">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-[#181818] shadow border-b border-white/10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <button id="chat-launcher" onclick="toggleChat()" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; background: #e63946; color: #fff; border: none; padding: 15px 22px; border-radius: 30px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">Chat with Us</button>

        <div id="chat-modal" style="display: none; position: fixed; bottom: 80px; right: 20px; width: min(340px, calc(100vw - 24px)); height: 460px; max-height: 70vh; background: #1e1e1e; border-radius: 12px; border: 1px solid #333; flex-direction: column; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.5); z-index: 99999;">
            <div style="background: #e63946; padding: 14px; font-weight: bold; color: white;">Infliction Gym Support</div>
            <div id="chat-body" role="log" aria-live="polite" style="flex: 1; padding: 12px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; background: #1f1f1f;">
                <div style="padding: 8px 12px; border-radius: 8px; max-width: 80%; font-size: 14px; line-height: 1.4; background: #2a2a2a; border: 1px solid #333; align-self: flex-start; color: white;">Hi! Welcome to Infliction Gym Magalang. How can I help you today?</div>
            </div>
            <div style="padding: 10px; display: flex; gap: 6px; background: #181818; border-top: 1px solid #333;">
                <input type="text" id="chat-input" placeholder="Type your message..." style="flex: 1; padding: 10px 12px; border-radius: 4px; border: 1px solid #444; background: #222; color: #fff;">
                <button id="chat-send-btn" style="padding: 8px 14px; background: #e63946; color: white; border: none; border-radius: 4px; cursor: pointer;">Send</button>
            </div>
        </div>

        <script>
            let sessionId = localStorage.getItem('infliction_session') || 'sess_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('infliction_session', sessionId);
            let isSending = false;

            function toggleChat() {
                const modal = document.getElementById('chat-modal');
                if (modal) {
                    modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
                }
            }

            function triggerChat(message) {
                const modal = document.getElementById('chat-modal');
                const input = document.getElementById('chat-input');

                if (modal) {
                    modal.style.display = 'flex';
                }

                if (input) {
                    input.value = message;
                    sendMsg();
                }
            }

            async function sendMsg() {
                const input = document.getElementById('chat-input');
                const body = document.getElementById('chat-body');
                const text = input ? input.value.trim() : '';

                if (!text || !body || isSending) return;

                isSending = true;
                const sendButton = document.getElementById('chat-send-btn');
                const originalSendLabel = sendButton ? sendButton.textContent : '';
                if (sendButton) {
                    sendButton.disabled = true;
                    sendButton.textContent = 'Sending...';
                    sendButton.style.opacity = '0.65';
                    sendButton.style.cursor = 'wait';
                }
                body.innerHTML += `<div style="padding: 8px 12px; border-radius: 8px; max-width: 80%; font-size: 14px; line-height: 1.4; background: #e63946; align-self: flex-end; color: white;">${escapeHtml(text)}</div>`;
                input.value = '';
                body.scrollTop = body.scrollHeight;

                try {
                    const response = await fetch('/api/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ session_id: sessionId, message: text })
                    });

                    const data = await response.json();
                    body.innerHTML += `<div style="padding: 8px 12px; border-radius: 8px; max-width: 80%; font-size: 14px; line-height: 1.4; background: #2a2a2a; border: 1px solid #333; align-self: flex-start; color: white;">${escapeHtml(data.reply || 'Sorry, I could not answer that right now. Please email InflictionGym@gmail.com')}</div>`;
                    body.scrollTop = body.scrollHeight;
                } catch (err) {
                    body.innerHTML += `<div style="padding: 8px 12px; border-radius: 8px; max-width: 80%; font-size: 14px; line-height: 1.4; background: #2a2a2a; border: 1px solid #333; align-self: flex-start; color: white;">Sorry, something went wrong. Please email InflictionGym@gmail.com</div>`;
                } finally {
                    isSending = false;
                    if (sendButton) {
                        sendButton.disabled = false;
                        sendButton.textContent = originalSendLabel;
                        sendButton.style.opacity = '';
                        sendButton.style.cursor = '';
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                document.addEventListener('submit', function (event) {
                    const form = event.target.closest('form[data-loading-label]');
                    const button = form ? form.querySelector('button[type="submit"]') : null;

                    if (button && !button.disabled) {
                        button.disabled = true;
                        button.textContent = form.dataset.loadingLabel;
                        button.setAttribute('aria-busy', 'true');
                    }
                });

                const input = document.getElementById('chat-input');
                const sendBtn = document.getElementById('chat-send-btn');

                if (input) {
                    input.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            sendMsg();
                        }
                    });
                }

                if (sendBtn) {
                    sendBtn.addEventListener('click', function () {
                        sendMsg();
                    });
                }
            });

            function escapeHtml(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }
        </script>
    </body>
</html>
