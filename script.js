const chatContainer = document.getElementById('chat-container');
const userInput = document.getElementById('user-input');
const loader = document.getElementById('loader');
const progressBar = document.querySelector('#loader .progress');

function displayMessage(text, sender) {
    const messageContainer = document.createElement('div');
    messageContainer.classList.add('message-container', sender);
    const messageBubble = document.createElement('div');
    messageBubble.classList.add('message-bubble', sender);

    // Convert Markdown to HTML for display
    const html = marked(text);
    messageBubble.innerHTML = html;

    messageContainer.appendChild(messageBubble);
    chatContainer.appendChild(messageContainer);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showLoader() {
    loader.style.display = 'block';
}

function hideLoader() {
    loader.style.display = 'none';
}

function startLoader() {
    progressBar.classList.add('is-indeterminate');
}

function stopLoader() {
    progressBar.classList.remove('is-indeterminate');
}

function sendMessage() {
    const message = userInput.value;
    displayMessage(message, 'user');

    showLoader();
    startLoader();

    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'message=' + encodeURIComponent(message),
    })
        .then(response => response.text())
        .then(text => {
            displayMessage(text, 'assistant');
            hideLoader();
            stopLoader();
        })
        .catch(error => {
            console.error('An error occurred:', error);
            hideLoader();
            stopLoader();
        });

    userInput.value = '';
}

function clearSession() {
    showLoader();
    startLoader();

    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'message=clear-session',
    })
        .then(() => {
            chatContainer.innerHTML = '';
            hideLoader();
            stopLoader();
        })
        .catch(error => {
            console.error('An error occurred:', error);
            hideLoader();
            stopLoader();
        });
}

// Add event listeners
userInput.addEventListener('keypress', (event) => {
    if (event.key === 'Enter') {
        sendMessage();
    }
});

document.getElementById('send-button').addEventListener('click', sendMessage);

document.getElementById('clear-button').addEventListener('click', clearSession);
