/**
 * AI Chatbot Widget for LuxeAuto Parts
 * Production-ready chatbot with real-time messaging
 */

(function() {
    'use strict';

    var CONFIG = {
        apiUrl: null,
        widgetId: 'chatbot-widget',
        typingSpeed: 50,
        maxMessages: 50,
        animationDuration: 300
    };

    var state = {
        isOpen: false,
        messages: [],
        isTyping: false,
        hasNewMessage: false,
        sessionId: null
    };

    function getApiUrl() {
        if (CONFIG.apiUrl) return CONFIG.apiUrl;
        
        var path = window.location.pathname;
        var segments = path.split('/');
        var projectRoot = '';
        
        for (var i = 0; i < segments.length; i++) {
            if (segments[i] === 'api' || segments[i] === 'admin') {
                projectRoot = segments.slice(0, i).join('/') || '/';
                break;
            }
        }
        
        if (!projectRoot) {
            projectRoot = '/aksesoris_mobil';
        }
        
        CONFIG.apiUrl = projectRoot + '/api/chatbot_handler.php';
        return CONFIG.apiUrl;
    }

    function createWidget() {
        var widget = document.createElement('div');
        widget.id = CONFIG.widgetId;
        widget.className = 'chat-widget';
        widget.innerHTML = getWidgetHTML();
        document.body.appendChild(widget);
        initializeWidget();
    }

    function getWidgetHTML() {
        return '<div class="chat-button" id="chat-toggle" aria-label="Buka chat" tabindex="0">' +
            '<svg class="chat-icon" viewBox="0 0 24 24">' +
                '<path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>' +
            '</svg>' +
            '<svg class="close-icon" viewBox="0 0 24 24" style="display:none;">' +
                '<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>' +
            '</svg>' +
            '<span class="chat-badge" id="chat-badge" style="display:none;">1</span>' +
        '</div>' +
        '<div class="chat-window" id="chat-window" aria-hidden="true">' +
            '<div class="chat-header">' +
                '<div class="chat-avatar">' +
                    '<svg viewBox="0 0 24 24">' +
                        '<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>' +
                    '</svg>' +
                '</div>' +
                '<div class="chat-header-info">' +
                    '<h3>LuxeAuto Assistant</h3>' +
                    '<span>Online</span>' +
                '</div>' +
                '<button class="chat-close" id="chat-close" aria-label="Tutup chat">' +
                    '<svg viewBox="0 0 24 24">' +
                        '<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>' +
                    '</svg>' +
                '</button>' +
            '</div>' +
            '<div class="chat-messages" id="chat-messages"></div>' +
            '<div class="chat-input-area">' +
                '<div class="chat-input-wrapper">' +
                    '<input type="text" class="chat-input" id="chat-input" placeholder="Ketik pesan..." autocomplete="off" aria-label="Ketik pesan">' +
                    '<button class="chat-send" id="chat-send" aria-label="Kirim pesan">' +
                        '<svg viewBox="0 0 24 24">' +
                            '<path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>' +
                        '</svg>' +
                    '</button>' +
                '</div>' +
            '</div>' +
        '</div>';
    }

    function initializeWidget() {
        var toggleBtn = document.getElementById('chat-toggle');
        var closeBtn = document.getElementById('chat-close');
        var sendBtn = document.getElementById('chat-send');
        var input = document.getElementById('chat-input');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleChat);
            toggleBtn.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' || e.key === ' ') toggleChat();
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', toggleChat);
        }

        if (sendBtn) {
            sendBtn.addEventListener('click', sendMessage);
        }

        if (input) {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') sendMessage();
            });
        }

        setTimeout(function() {
            addWelcomeMessage();
        }, 500);
    }

    function toggleChat() {
        var windowEl = document.getElementById('chat-window');
        var toggleBtn = document.getElementById('chat-toggle');
        var badge = document.getElementById('chat-badge');

        if (!windowEl) return;

        state.isOpen = !state.isOpen;

        if (state.isOpen) {
            windowEl.classList.add('open');
            windowEl.setAttribute('aria-hidden', 'false');
            toggleBtn.classList.add('open');
            var chatIcon = toggleBtn.querySelector('.chat-icon');
            var closeIcon = toggleBtn.querySelector('.close-icon');
            if (chatIcon) chatIcon.style.display = 'none';
            if (closeIcon) closeIcon.style.display = 'block';
            if (badge) badge.style.display = 'none';
            state.hasNewMessage = false;
            setTimeout(function() {
                var input = document.getElementById('chat-input');
                if (input) input.focus();
            }, 100);
        } else {
            windowEl.classList.remove('open');
            windowEl.setAttribute('aria-hidden', 'true');
            toggleBtn.classList.remove('open');
            var chatIcon = toggleBtn.querySelector('.chat-icon');
            var closeIcon = toggleBtn.querySelector('.close-icon');
            if (chatIcon) chatIcon.style.display = 'block';
            if (closeIcon) closeIcon.style.display = 'none';
        }
    }

    function addWelcomeMessage() {
        var welcomeMsg = {
            type: 'bot',
            text: 'Halo! Saya asisten virtual LuxeAuto Parts.\n\nSaya siap membantu Anda dengan:\n- Informasi produk & harga\n- Masalah keranjang belanja\n- Panduan checkout & pembayaran\n- Pertanyaan umum\n\nAda yang bisa saya bantu hari ini?',
            time: new Date()
        };

        addMessage(welcomeMsg);

        setTimeout(function() {
            addQuickReplies([
                { label: 'Lihat Produk', action: 'products' },
                { label: 'Bantuan Cart', action: 'cart_help' },
                { label: 'Cara Checkout', action: 'checkout_help' },
                { label: 'Hubungi CS', action: 'contact' }
            ]);
        }, 500);
    }

    function addMessage(message) {
        var messagesContainer = document.getElementById('chat-messages');
        if (!messagesContainer) return;

        var messageEl = createMessageElement(message);
        messagesContainer.appendChild(messageEl);
        scrollToBottom(messagesContainer);
        state.messages.push(message);

        if (state.messages.length > CONFIG.maxMessages) {
            state.messages.shift();
        }
    }

    function createMessageElement(message) {
        var div = document.createElement('div');
        var isUser = message.type === 'user';
        div.className = 'chat-message ' + message.type;

        var avatarSvg = isUser
            ? '<svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>'
            : '<svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';

        var formattedText = formatMessageText(message.text);

        div.innerHTML =
            '<div class="message-avatar">' + avatarSvg + '</div>' +
            '<div class="message-content">' +
                '<div class="message-bubble">' + formattedText + '</div>' +
                '<span class="message-time">' + formatTime(message.time) + '</span>' +
            '</div>';

        return div;
    }

    function formatMessageText(text) {
        if (!text) return '';
        text = text.replace(/&/g, '&amp;')
                   .replace(/</g, '<')
                   .replace(/>/g, '>');
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        text = text.replace(/\n/g, '<br>');
        return text;
    }

    function formatTime(date) {
        if (!date) return '';
        var d = new Date(date);
        var hours = d.getHours().toString().padStart(2, '0');
        var minutes = d.getMinutes().toString().padStart(2, '0');
        return hours + ':' + minutes;
    }

    function scrollToBottom(container) {
        container.scrollTop = container.scrollHeight;
    }

    function addQuickReplies(replies) {
        var messagesContainer = document.getElementById('chat-messages');
        if (!messagesContainer) return;

        var container = document.createElement('div');
        container.className = 'quick-replies';

        replies.forEach(function(reply) {
            var btn = document.createElement('button');
            btn.className = 'quick-reply';
            btn.textContent = reply.label;
            btn.addEventListener('click', function() {
                handleQuickReply(reply);
            });
            container.appendChild(btn);
        });

        messagesContainer.appendChild(container);
        scrollToBottom(messagesContainer);
    }

    function handleQuickReply(reply) {
        var userMsg = {
            type: 'user',
            text: reply.label,
            time: new Date()
        };
        addMessage(userMsg);
        showTypingIndicator();
        sendToApi('', 'quick_reply', reply.action);
    }

    function showTypingIndicator() {
        var messagesContainer = document.getElementById('chat-messages');
        if (!messagesContainer) return;

        var typingEl = document.createElement('div');
        typingEl.className = 'chat-message bot typing-indicator';
        typingEl.id = 'typing-indicator';
        typingEl.innerHTML =
            '<div class="message-avatar">' +
                '<svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>' +
            '</div>' +
            '<div class="chat-typing">' +
                '<span class="typing-dot"></span>' +
                '<span class="typing-dot"></span>' +
                '<span class="typing-dot"></span>' +
            '</div>';

        messagesContainer.appendChild(typingEl);
        scrollToBottom(messagesContainer);
        state.isTyping = true;
    }

    function hideTypingIndicator() {
        var typingEl = document.getElementById('typing-indicator');
        if (typingEl) {
            typingEl.remove();
        }
        state.isTyping = false;
    }

    function sendMessage() {
        var input = document.getElementById('chat-input');
        var message = input ? input.value.trim() : '';

        if (!message || state.isTyping) return;

        var userMsg = {
            type: 'user',
            text: message,
            time: new Date()
        };
        addMessage(userMsg);
        if (input) input.value = '';
        showTypingIndicator();
        sendToApi(message, 'chat', '');
    }

    function sendToApi(message, action, replyAction) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', getApiUrl(), true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        var payload = { message: message, action: action };
        if (replyAction) {
            payload.reply_action = replyAction;
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                hideTypingIndicator();

                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success && response.reply) {
                            var botMsg = {
                                type: 'bot',
                                text: response.reply,
                                time: new Date()
                            };
                            addMessage(botMsg);

                            if (response.quick_replies && response.quick_replies.length > 0) {
                                setTimeout(function() {
                                    addQuickReplies(response.quick_replies);
                                }, 300);
                            }
                        }
                    } catch (e) {
                        addMessage({
                            type: 'bot',
                            text: 'Maaf, terjadi kesalahan. Silakan coba lagi.',
                            time: new Date()
                        });
                    }
                } else {
                    addMessage({
                        type: 'bot',
                        text: 'Maaf, saya sedang mengalami masalah. Silakan coba lagi.',
                        time: new Date()
                    });
                }
            }
        };

        xhr.send(JSON.stringify(payload));
    }

    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', createWidget);
        } else {
            createWidget();
        }
    }

    init();

    window.Chatbot = {
        open: function() { if (!state.isOpen) toggleChat(); },
        close: function() { if (state.isOpen) toggleChat(); },
        toggle: toggleChat,
        sendMessage: function(message) {
            if (message) {
                var input = document.getElementById('chat-input');
                if (input) input.value = message;
                sendMessage();
            }
        }
    };

})();

