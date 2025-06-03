document.addEventListener('DOMContentLoaded', () => {
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-chat-btn');
    const chatMessages = document.getElementById('chat-messages');
    const analysisResult = document.getElementById('ai-analysis-result');

    marked.setOptions({
        highlight: function (code, lang) {
            if (lang && hljs.getLanguage(lang)) {
                return hljs.highlight(code, {language: lang}).value;
            } else {
                return hljs.highlightAuto(code).value;
            }
        }
    });


    function analyzeError() {


        analysisResult.style.display = 'block';
        analysisResult.innerHTML = '<div class="loading">🤖 Analyzing your error...</div>';

        fetch(window.aiAssistantEndpoints.analyze, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(window.aiAssistantContext)
        })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    analysisResult.innerHTML = `<div class="error">❌ ${data.message}</div>`;
                } else {
                    analysisResult.innerHTML = `
                        <div class="analysis-result">
                            <div class="analysis-result-section cause">
                                <div class="analysis-header">Cause:</div>
                                <div class="analysis-body">${data.cause??""}</div>
                            </div>
                            <div class="analysis-result-section solution">
                                <div class="analysis-header">Solution:</div>
                                <div class="analysis-body">${marked.parse(data.solution??"")}</div>
                            </div>
                            <div class="analysis-result-section suggestions">
                                <div class="analysis-header">Suggestions:</div>
                                <div class="analysis-body">${marked.parse(data.code_example??"")}</div>
                            </div>
                        </div>
                    `;
                    hljs.highlightAll();
                }

            })
            .catch(err => {
                analysisResult.innerHTML = `<div class="error">❌ Failed to read the analysis</div>`;
                console.error('Error analyzing:', err.message);
            })
            .finally(() => {

            });
    }

    function addMessage(content, isUser = false) {
        const msg = document.createElement('div');
        msg.className = `message ${isUser ? 'user-message' : 'ai-message'}`;
        msg.innerHTML = `<strong>${isUser ? 'You' : 'AI Assistant'}:</strong> ${content}`;
        chatMessages.appendChild(msg);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function sendChatMessage() {
        const message = chatInput.value.trim();
        if (!message) return;

        addMessage(message, true);
        chatInput.value = '';
        sendBtn.disabled = true;
        sendBtn.textContent = 'Sending...';

        const loading = document.createElement('div');
        loading.className = 'message ai-message loading-message';
        loading.innerHTML = '<strong>AI Assistant:</strong> <em>Thinking...</em>';
        chatMessages.appendChild(loading);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        fetch(window.aiAssistantEndpoints.chat, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                message,
                context: window.aiAssistantContext
            })
        })
            .then(res => res.json())
            .then(data => {
                chatMessages.removeChild(loading);
                if (data.error) {
                    addMessage(`❌ ${data.message}`, false);
                } else {
                    addMessage(marked.parse(data.response) || "I couldn't find an answer, please try rephrasing.", false);
                }
                hljs.highlightAll();
            })
            .catch(err => {
                chatMessages.removeChild(loading);
                addMessage(`❌ Error: ${err.message}`, false);
            })
            .finally(() => {
                sendBtn.disabled = false;
                sendBtn.textContent = 'Send';
            });
    }


    if (sendBtn) {
        sendBtn.addEventListener('click', sendChatMessage);
    }

    if (chatInput) {
        chatInput.addEventListener('keypress', e => {
            if (e.key === 'Enter') sendChatMessage();
        });
    }

    analyzeError();
});
