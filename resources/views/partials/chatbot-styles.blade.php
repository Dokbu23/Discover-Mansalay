<style>
.login-chatbot-widget {
    position: fixed;
    right: 1.5rem;
    bottom: 1.5rem;
    z-index: 1000;
}

.login-chatbot-toggle {
    width: 62px;
    height: 62px;
    border: 0;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    background: linear-gradient(135deg, #be185d 0%, #db2777 100%);
    box-shadow: 0 10px 30px rgba(190, 24, 93, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.login-chatbot-toggle:hover {
    transform: scale(1.06);
    box-shadow: 0 14px 35px rgba(190, 24, 93, 0.42);
}

.login-chatbot-toggle svg {
    width: 28px;
    height: 28px;
}

.login-chatbot-panel {
    position: absolute;
    right: 0;
    bottom: 76px;
    width: 350px;
    max-height: 520px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 16px 45px rgba(0, 0, 0, 0.18);
    display: none;
    overflow: hidden;
    border: 1px solid #f4d3e5;
}

.login-chatbot-panel.active {
    display: flex;
    flex-direction: column;
}

.chatbot-header {
    padding: 0.9rem 1rem;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #be185d 0%, #db2777 100%);
}

.chatbot-header h3 {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
}

.chatbot-header span {
    display: block;
    font-size: 0.75rem;
    opacity: 0.9;
}

.chatbot-close {
    border: 0;
    background: transparent;
    color: #fff;
    cursor: pointer;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chatbot-close svg {
    width: 20px;
    height: 20px;
}

.chatbot-messages {
    flex: 1;
    overflow-y: auto;
    background: #fff7fb;
    padding: 0.9rem;
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
}

.chat-msg {
    max-width: 88%;
    border-radius: 12px;
    padding: 0.65rem 0.75rem;
    font-size: 0.86rem;
    line-height: 1.45;
    white-space: pre-line;
}

.chat-msg.bot {
    align-self: flex-start;
    border-top-left-radius: 4px;
    background: #fff;
    color: #3f2140;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.chat-msg.user {
    align-self: flex-end;
    border-top-right-radius: 4px;
    color: #fff;
    background: linear-gradient(135deg, #be185d 0%, #db2777 100%);
}

.chatbot-suggestions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
    padding: 0.7rem 0.9rem;
    border-top: 1px solid #f1d9e7;
    background: #fff;
}

.chatbot-suggestion-btn {
    border: 1px solid #ef9fc5;
    background: #fff;
    color: #9d174d;
    border-radius: 999px;
    font-size: 0.76rem;
    padding: 0.35rem 0.6rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.chatbot-suggestion-btn:hover {
    background: #fde7f3;
}

.chatbot-input-wrap {
    border-top: 1px solid #f1d9e7;
    background: #fff;
    padding: 0.7rem;
    display: flex;
    gap: 0.5rem;
}

.chatbot-input-wrap input {
    flex: 1;
    border: 1px solid #e9bed5;
    border-radius: 10px;
    padding: 0.6rem 0.7rem;
    font-size: 0.86rem;
    font-family: inherit;
}

.chatbot-input-wrap input:focus {
    outline: none;
    border-color: #db2777;
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.14);
}

.chatbot-send {
    border: 0;
    border-radius: 10px;
    min-width: 78px;
    font-size: 0.83rem;
    font-weight: 600;
    font-family: inherit;
    color: #fff;
    cursor: pointer;
    background: linear-gradient(135deg, #be185d 0%, #db2777 100%);
}

.chatbot-send:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@media (max-width: 900px) {
    .login-chatbot-panel {
        width: min(360px, calc(100vw - 1.2rem));
        right: -0.3rem;
    }

    .login-chatbot-widget {
        right: 0.8rem;
        bottom: 0.8rem;
    }
}
</style>
