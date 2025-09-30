class SuitBotChat {
    constructor() {
        this.isOpen = false;
        this.isLoading = false;
        this.messages = [];
        this.sessionId = this.getOrCreateSessionId();
        this.initializeElements();
        this.bindEvents();
        this.loadChatHistory();
        this.addWelcomeMessage();
    }

    initializeElements() {
        this.chatToggle = document.getElementById('chatToggle');
        this.chatModal = document.getElementById('chatModal');
        this.chatIcon = document.getElementById('chatIcon');
        this.minimizeBtn = document.getElementById('minimizeBtn');
        this.messagesArea = document.getElementById('messagesArea');
        this.messageForm = document.getElementById('messageForm');
        this.messageInput = document.getElementById('messageInput');
        this.sendBtn = document.getElementById('sendBtn');
        this.headerStatus = document.getElementById('headerStatus');
        this.statusText = document.getElementById('statusText');
        this.mobileOverlay = document.getElementById('mobileOverlay');
    }

    bindEvents() {
        this.chatToggle.addEventListener('click', () => this.toggleChat());
        this.minimizeBtn.addEventListener('click', () => this.toggleChat());
        this.messageForm.addEventListener('submit', (e) => this.handleSubmit(e));
        this.messageInput.addEventListener('keypress', (e) => this.handleKeyPress(e));
        this.mobileOverlay.addEventListener('click', () => this.toggleChat());
    }
    getOrCreateSessionId() {
        let sessionId = localStorage.getItem('chatSessionId');
        if (!sessionId) {
            sessionId = this.generateUUID();
            localStorage.setItem('chatSessionId', sessionId);
        }
        return sessionId;
    }

    generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0;
            const v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    toggleChat() {
        this.isOpen = !this.isOpen;

        if (this.isOpen) {
            this.openChat();
        } else {
            this.closeChat();
        }
    }

    openChat() {
        this.chatModal.style.pointerEvents = 'auto';
        this.chatModal.classList.remove('opacity-0', 'translate-y-8', 'scale-95');
        this.chatModal.classList.add('opacity-100', 'translate-y-0', 'scale-100', 'chat-open');
        this.mobileOverlay.style.pointerEvents = 'none';
        this.mobileOverlay.classList.remove('opacity-0');
        this.mobileOverlay.classList.add('opacity-100');

        setTimeout(() => {
            this.messageInput.focus();
        }, 300);
    }

    closeChat() {
        this.chatModal.classList.add('opacity-0', 'translate-y-8', 'scale-95');
        this.chatModal.classList.remove('opacity-100', 'translate-y-0', 'scale-100', 'chat-open');
        this.mobileOverlay.classList.add('opacity-0');
        this.mobileOverlay.classList.remove('opacity-100');

        setTimeout(() => {
            this.chatModal.style.pointerEvents = 'none';
            this.mobileOverlay.style.pointerEvents = 'none';
        }, 500);
    }

    addWelcomeMessage() {
        const storedMessages = localStorage.getItem('chatHistory');
        if (!storedMessages || JSON.parse(storedMessages).length === 0) {
            const welcomeMessage = {
                id: this.generateUUID(),
                type: 'bot',
                content: 'Olá! Sou o Sales IA 🤖. Em que posso te ajudar hoje?',
                timestamp: new Date().toISOString()
            };
            this.messages = [welcomeMessage];
            this.renderMessage(welcomeMessage);
            this.saveChatHistory();
            this.showSuggestions();
        }
    }
    showSuggestions() {
        const suggestions = [
            'Como crio um infoproduto?',
            'Como configurar a mensageria?',
            'Como habilitar o programa de Afiliados?'
        ];

        const suggestionsContainer = document.createElement('div');
        suggestionsContainer.className = 'space-y-3 mt-6';
        suggestionsContainer.innerHTML = `
                    <p class="text-gray-600 text-sm font-medium px-2">Sugestões para começar:</p>
                    ${suggestions.map(suggestion => `
                        <button 
                            class="suggestion-btn block w-full text-left p-4 bg-white hover:bg-lime-50 text-gray-700 hover:text-gray-900 rounded-xl text-sm font-medium transition-all duration-200 border border-gray-200 hover:border-lime-300 shadow-sm hover:shadow-md transform hover:scale-[1.02]"
                            data-suggestion="${suggestion}"
                        >
                            ${suggestion}
                        </button>
                    `).join('')}
                `;

        this.messagesArea.appendChild(suggestionsContainer);

        suggestionsContainer.querySelectorAll('.suggestion-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const suggestion = e.target.getAttribute('data-suggestion');
                this.messageInput.value = suggestion;
                suggestionsContainer.remove();
                setTimeout(() => this.sendMessage(suggestion), 100);
            });
        });

        this.scrollToBottom();
    }

    loadChatHistory() {
        const storedMessages = localStorage.getItem('chatHistory');
        if (storedMessages) {
            this.messages = JSON.parse(storedMessages);
            this.messages.forEach(message => this.renderMessage(message));
        }
    }

    saveChatHistory() {
        localStorage.setItem('chatHistory', JSON.stringify(this.messages));
    }

    renderMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${message.type === 'user' ? 'justify-end' : 'justify-start'} message-enter`;

        if (message.id === 'typing') {
            messageDiv.innerHTML = `
                        <div  style="max-width: 280px" class="px-4 py-3 rounded-2xl shadow-sm bg-[#30a72d] text-white rounded-bl-md"> 
                            <div class="flex items-center space-x-1">
                                <span class="text-sm font-medium">Sales IA está digitando</span>
                                <div class="flex space-x-1 ml-2">
                                    <div class="w-2 h-2 bg-white rounded-full bounce-dot"></div>
                                    <div class="w-2 h-2 bg-white rounded-full bounce-dot"></div>
                                    <div class="w-2 h-2 bg-white rounded-full bounce-dot"></div>
                                </div>
                            </div>
                        </div>
                    `;
        } else {
            const bgClass = message.type === 'user'
                ? 'bg-[#404040] rounded-br-md'
                : 'bg-[#30a72d] text-white rounded-bl-md';

            messageDiv.innerHTML = `
                        <div style="max-width: 280px" class=" px-4 py-3 rounded-2xl shadow-sm ${bgClass} transition-all duration-300 hover:shadow-md">
                            <p class="text-sm leading-relaxed font-medium text-white">${message.content}</p>
                        </div>
                    `;
        }

        this.messagesArea.appendChild(messageDiv);
        this.scrollToBottom();
    }

    scrollToBottom() {
        setTimeout(() => {
            this.messagesArea.scrollTop = this.messagesArea.scrollHeight;
        }, 100);
    }

    handleSubmit(e) {
        e.preventDefault();
        const message = this.messageInput.value.trim();
        if (message && !this.isLoading) {
            this.sendMessage(message);
        }
    }

    handleKeyPress(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            this.handleSubmit(e);
        }
    }

    async sendMessage(messageText) {
        if (this.isLoading) return;

        const userMessage = {
            id: this.generateUUID(),
            type: 'user',
            content: messageText,
            timestamp: new Date().toISOString()
        };

        this.messages.push(userMessage);
        this.renderMessage(userMessage);
        this.messageInput.value = '';
        this.isLoading = true;
        this.updateStatus('connecting');

        const typingMessage = {
            id: 'typing',
            type: 'bot',
            content: '...',
            timestamp: new Date().toISOString()
        };
        this.renderMessage(typingMessage);

        this.messageInput.disabled = true;
        this.sendBtn.disabled = true;

        try {
            const response = await fetch('/api/v1/data/webhookn8n', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    action: 'sendMessage',
                    sessionId: this.sessionId,
                    chatInput: messageText
                })
            });

            this.removeTypingMessage();

            if (response.ok) {
                const data = await response.json();
                const botResponse = data.output || 'Desculpe, não consegui entender sua pergunta. Pode tentar novamente?';

                const botMessage = {
                    id: this.generateUUID(),
                    type: 'bot',
                    content: botResponse,
                    timestamp: new Date().toISOString()
                };

                this.messages.push(botMessage);
                this.renderMessage(botMessage);
                this.updateStatus('online');
            } else {
                throw new Error('Falha na requisição');
            }
        } catch (error) {
            this.removeTypingMessage();

            const errorMessage = {
                id: this.generateUUID(),
                type: 'bot',
                content: 'Desculpe, não consegui entender sua pergunta. Pode tentar novamente?',
                timestamp: new Date().toISOString()
            };

            this.messages.push(errorMessage);
            this.renderMessage(errorMessage);
            this.updateStatus('offline');
        } finally {
            this.isLoading = false;
            this.messageInput.disabled = false;
            this.sendBtn.disabled = false;
            this.messageInput.focus();
            this.saveChatHistory();
        }
    }

    removeTypingMessage() {
        const typingElements = this.messagesArea.querySelectorAll('.message-enter');
        typingElements.forEach(el => {
            if (el.textContent.includes('Sales IA está digitando')) {
                el.remove();
            }
        });
    }

    updateStatus(status) {
        const statusClasses = {
            online: { color: 'bg-emerald-500', text: 'Online' },
            connecting: { color: 'bg-emerald-500', text: 'Online' },
            offline: { color: 'bg-red-500', text: 'Offline' }
        };

        const statusConfig = statusClasses[status];
        this.headerStatus.className = `absolute -bottom-1 -right-1 w-4 h-4 ${statusConfig.color} rounded-full border-2 border-white shadow-sm`;
        this.statusText.textContent = statusConfig.text;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    new SuitBotChat();
});