<div class="login-chatbot-widget">
    <button type="button" class="login-chatbot-toggle" id="loginChatbotToggle" aria-label="Open chatbot">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.394-3.719C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
    </button>

    <div class="login-chatbot-panel" id="loginChatbotPanel">
        <div class="chatbot-header">
            <div>
                <h3>Discover Assistant</h3>
                <span>Website-aware helper</span>
            </div>
            <button type="button" class="chatbot-close" id="loginChatbotClose" aria-label="Close chatbot">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="chatbot-messages" id="chatbotMessages">
            <div class="chat-msg bot">Hello! I can answer questions about this website like resorts, products, events, promotions, and login help.</div>
        </div>

        <div class="chatbot-suggestions" id="chatbotSuggestions">
            <button type="button" class="chatbot-suggestion-btn">How many resorts are available?</button>
            <button type="button" class="chatbot-suggestion-btn">Show active promotions</button>
            <button type="button" class="chatbot-suggestion-btn">Upcoming events</button>
        </div>

        <form class="chatbot-input-wrap" id="loginChatbotForm">
            <input type="text" id="chatbotInput" placeholder="Ask about this website..." maxlength="500" required>
            <button type="submit" class="chatbot-send" id="chatbotSendBtn">Send</button>
        </form>
    </div>
</div>
