<?php
$pageHeader = "Messages";
if (file_exists('../includes/navbar.php')) {
    include '../includes/navbar.php';
} else {
    echo "<p class='text-red-500'>Error: Navbar file not found.</p>";
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Pass session variables to JavaScript
echo "<script>
    const senderId = {$_SESSION['user_id']};
    const username = '" . htmlspecialchars($_SESSION['username'], ENT_QUOTES) . "';
</script>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen w-full flex flex-col">
<div class="container mx-auto mt-12">
        <!-- Informational Section -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
            <h2 class="text-blue-700 font-bold text-lg">DM</h2>
            <p class="text-gray-700">
            The Direct Message feature lets you connect directly with runners and exercise buddies found through the Find-A-Buddy tool or Matching Widget. Plan workouts, coordinate schedules, and stay connected with your fitness partners in private, organized chats.            </p>
 </div>

<div class="container mx-auto mt-10 w-full p-4 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold text-blue-800 mb-6 text-center">Messages</h1>

    <!-- Messages Threads Container -->
    <div id="messages" class="space-y-4 max-h-96 overflow-y-auto p-4">
        <!-- Chat threads will be dynamically populated -->
    </div>

    <!-- Message Input Section -->
    <div class="p-4 border-t border-gray-200">
        <textarea id="messageContent" placeholder="Type your message..." rows="3"
            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

        <button id="sendMessage"
            class="mt-2 w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none">
            Send Message
        </button>

        <div id="messageBox" class="mt-2 text-center"></div>
    </div>
</div>

<script>
// Function to extract a query parameter from the URL
function getUrlParameter(name) {
    name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
    const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    const results = regex.exec(window.location.search);
    return results === null ? null : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

// Global variable to track the currently selected recipient
let currentRecipientId = null;

// Fetch and display messages grouped by users, with an optional callback
function fetchMessages(callback) {
    fetch('../backend/fetch_messages.php')
        .then(response => response.json())
        .then(data => {
            const messagesDiv = document.getElementById('messages');
            messagesDiv.innerHTML = ''; // Clear previous messages

            // Group messages by other_user_id
            for (const userId in data) {
                const user = data[userId];

                const threadDiv = document.createElement('div');
                threadDiv.className = 'border border-gray-300 rounded-lg shadow-md mb-4';

                threadDiv.innerHTML = `
                    <div class="bg-blue-500 text-white p-4 rounded-t-lg flex justify-between items-center">
                        <h2 class="font-bold">${user.username}</h2>
                        <button onclick="setCurrentChat('${userId}')"
                            class="bg-white text-blue-500 px-3 py-1 rounded hover:bg-gray-200">
                            Chat
                        </button>
                    </div>
                    <div id="chat-${userId}" class="p-4 bg-gray-50 space-y-2 hidden">
                        ${user.messages.map(msg => `
                            <div class="${msg.is_sent_by_user ? 'text-right' : 'text-left'}">
                                <div class="inline-block px-4 py-2 rounded-lg ${msg.is_sent_by_user ? 'bg-green-100' : 'bg-blue-100'}">
                                    ${msg.content}
                                </div>
                                <small class="block text-gray-500">${new Date(msg.timestamp).toLocaleString()}</small>
                            </div>
                        `).join('')}
                    </div>
                `;
                messagesDiv.appendChild(threadDiv);
            }

            // If a callback is passed, execute it after the fetch is complete
            if (typeof callback === "function") {
                callback();
            }
        });
}

// Set the current chat and display it
function setCurrentChat(userId) {
    // Hide all other chats
    const allChats = document.querySelectorAll('[id^="chat-"]');
    allChats.forEach(chat => chat.classList.add('hidden'));

    // Show the selected chat
    const chatDiv = document.getElementById(`chat-${userId}`);
    if (chatDiv) {
        chatDiv.classList.remove('hidden');
    }

    // Update the global recipient ID
    currentRecipientId = userId;
}

// Send a new message
document.getElementById('sendMessage').addEventListener('click', () => {
    const content = document.getElementById('messageContent').value;
    const receiverId = currentRecipientId; // Use the stored recipient ID

    if (!content.trim()) {
        return; // Don't send empty messages
    }

    if (!receiverId) {
        const messageBox = document.getElementById('messageBox');
        messageBox.innerHTML = `<p class="text-red-500">No recipient selected. Please select a chat first.</p>`;
        return;
    }

    fetch('../backend/send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sender_id: senderId, receiver_id: receiverId, content }),
    })
    .then(response => response.json())
    .then(result => {
        const messageBox = document.getElementById('messageBox');
        if (result.success) {
            messageBox.innerHTML = `<p class="text-green-500">${result.message}</p>`;

            // Remember the currently selected chat
            const rememberedChatId = currentRecipientId;

            // Refresh messages and reopen the same chat
            fetchMessages(() => {
                if (rememberedChatId) {
                    setCurrentChat(rememberedChatId);
                }
            });

            // Clear input
            document.getElementById('messageContent').value = '';
        } else {
            messageBox.innerHTML = `<p class="text-red-500">${result.message}</p>`;
        }
    });
});

// Check if there's a receiver_id parameter in the URL
const receiverParam = getUrlParameter('receiver_id');

// Initialize: fetch messages and if receiverParam is present, open that chat or create it
fetchMessages(() => {
    if (receiverParam) {
        let chatDiv = document.getElementById(`chat-${receiverParam}`);
        if (chatDiv) {
            // Chat exists from previous messages
            setCurrentChat(receiverParam);
        } else {
            // No chat found, fetch user info and create a new chat block
            fetch(`../backend/fetch_user_info.php?user_id=${receiverParam}`)
                .then(response => response.json())
                .then(userData => {
                    const username = userData.username || `User ${receiverParam}`;
                    const messagesDiv = document.getElementById('messages');

                    const threadDiv = document.createElement('div');
                    threadDiv.className = 'border border-gray-300 rounded-lg shadow-md mb-4';

                    threadDiv.innerHTML = `
                        <div class="bg-blue-500 text-white p-4 rounded-t-lg flex justify-between items-center">
                            <h2 class="font-bold">${username}</h2>
                            <button onclick="setCurrentChat('${receiverParam}')"
                                class="bg-white text-blue-500 px-3 py-1 rounded hover:bg-gray-200">
                                Chat
                            </button>
                        </div>
                        <div id="chat-${receiverParam}" class="p-4 bg-gray-50 space-y-2 hidden">
                            <p class="text-center text-gray-500">No messages yet. Start the conversation!</p>
                        </div>
                    `;

                    messagesDiv.appendChild(threadDiv);
                    setCurrentChat(receiverParam);
                })
                .catch(error => {
                    console.error("Error fetching user info:", error);
                });
        }
    }
});
</script>
</body>
</html>
