<script>
    (function () {
        const toggleBtn = document.getElementById('loginChatbotToggle');
        const closeBtn = document.getElementById('loginChatbotClose');
        const panel = document.getElementById('loginChatbotPanel');
        const form = document.getElementById('loginChatbotForm');
        const input = document.getElementById('chatbotInput');
        const sendBtn = document.getElementById('chatbotSendBtn');
        const messages = document.getElementById('chatbotMessages');
        const suggestions = document.getElementById('chatbotSuggestions');

        if (!toggleBtn || !closeBtn || !panel || !form || !input || !sendBtn || !messages || !suggestions) {
            return;
        }

        function appendMessage(text, role) {
            const bubble = document.createElement('div');
            bubble.className = 'chat-msg ' + role;
            bubble.textContent = text;
            messages.appendChild(bubble);
            messages.scrollTop = messages.scrollHeight;
        }

        function setSuggestions(items) {
            suggestions.innerHTML = '';

            (items || []).slice(0, 4).forEach(function (item) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'chatbot-suggestion-btn';
                btn.textContent = item;
                btn.addEventListener('click', function () {
                    input.value = item;
                    form.requestSubmit();
                });
                suggestions.appendChild(btn);
            });
        }

        async function askChatbot(messageText) {
            sendBtn.disabled = true;
            sendBtn.textContent = '...';

            try {
                const response = await fetch('{{ route("chatbot.ask") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: messageText })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    appendMessage('I could not process that right now. Please try again.', 'bot');
                    return;
                }

                appendMessage(data.reply || 'No response available.', 'bot');
                setSuggestions(data.suggestions || []);
            } catch (error) {
                appendMessage('Connection issue detected. Please try again in a moment.', 'bot');
            } finally {
                sendBtn.disabled = false;
                sendBtn.textContent = 'Send';
            }
        }

        toggleBtn.addEventListener('click', function () {
            panel.classList.toggle('active');

            if (panel.classList.contains('active')) {
                input.focus();
            }
        });

        closeBtn.addEventListener('click', function () {
            panel.classList.remove('active');
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const text = input.value.trim();
            if (!text) {
                return;
            }

            appendMessage(text, 'user');
            input.value = '';
            askChatbot(text);
        });

        suggestions.addEventListener('click', function (event) {
            if (!event.target.classList.contains('chatbot-suggestion-btn')) {
                return;
            }

            input.value = event.target.textContent;
            form.requestSubmit();
        });
    })();
</script>
