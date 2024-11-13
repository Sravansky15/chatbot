document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');

    // Add welcome message when chat loads
    appendMessage("Hello! I'm your AI assistant. How can I help you today? 👋", true);

    function appendMessage(message, isBot) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isBot ? 'bot-message' : 'user-message'}`;
        
        // Add typing animation for bot messages
        if (isBot) {
            messageDiv.style.opacity = '0';
            messageDiv.style.transform = 'translateY(10px)';
        }

        messageDiv.textContent = message;
        chatBox.appendChild(messageDiv);

        if (isBot) {
            // Trigger typing animation
            setTimeout(() => {
                messageDiv.style.transition = 'all 0.3s ease';
                messageDiv.style.opacity = '1';
                messageDiv.style.transform = 'translateY(0)';
            }, 100);
        }

        chatBox.scrollTop = chatBox.scrollHeight;
    }

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Display user message
        appendMessage(message, false);
        messageInput.value = '';

        try {
            const response = await fetch('chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: message })
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            
            // Add slight delay to make it feel more natural
            setTimeout(() => {
                appendMessage(data.response, true);
            }, 500);
        } catch (error) {
            console.error('Error:', error);
            setTimeout(() => {
                appendMessage('Sorry, there was an error processing your message. 😔', true);
            }, 500);
        }
    });

    // Add input placeholder rotation
    const placeholders = [
        "Type your message...",
        "Ask me anything...",
        "How can I help you?",
        "Let's chat...",
        "What's on your mind?"
    ];
    let currentPlaceholder = 0;

    setInterval(() => {
        messageInput.setAttribute('placeholder', placeholders[currentPlaceholder]);
        currentPlaceholder = (currentPlaceholder + 1) % placeholders.length;
    }, 3000);
});