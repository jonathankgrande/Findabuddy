<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen w-full flex flex-col">

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
 <div class="container mx-auto mt-12">
        <!-- Informational Section -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
            <h2 class="text-blue-700 font-bold text-lg">DM</h2>
            <p class="text-gray-700">
            The Direct Message feature lets you connect directly with runners and exercise buddies found through the Find-A-Buddy tool or Matching Widget. Plan workouts, coordinate schedules, and stay connected with your fitness partners in private, organized chats.            </p>
 </div>

<div class="container mx-auto mt-10 w-full bg-white shadow-md rounded-lg">

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
// Fetch and display messages grouped by users
function fetchMessages() {
    fetch('../backend/fetch_messages.php')
        .then(response => response.json())
        .then(data => {
            const messagesDiv = document.getElementById('messages');
            messagesDiv.innerHTML = ''; // Clear previous messages

            // Group messages by other_user_id
            for (const userId in data) {
                const user = data[userId];

                // Create a collapsible chat block
                const threadDiv = document.createElement('div');
                threadDiv.className = 'border border-gray-300 rounded-lg shadow-md';

                threadDiv.innerHTML = `
                    <div class="bg-blue-500 text-white p-4 rounded-t-lg flex justify-between items-center">
                        <h2 class="font-bold">${user.username}</h2>
                        <button onclick="toggleChat('chat-${userId}')"
                            class="bg-white text-blue-500 px-3 py-1 rounded hover:bg-gray-200">
                            Chat
                        </button>
                    </div>
                    <div id="chat-${userId}" class="hidden p-4 bg-gray-50 space-y-2">
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
        });
}

// Toggle visibility of chat messages
function toggleChat(chatId) {
    const chatDiv = document.getElementById(chatId);
    chatDiv.classList.toggle('hidden');
}

// Send a new message
document.getElementById('sendMessage').addEventListener('click', () => {
    const content = document.getElementById('messageContent').value;
    const receiverId = prompt("Enter the recipient's user ID:");

    if (!content.trim() || !receiverId) return;

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
            fetchMessages(); // Refresh the messages
            document.getElementById('messageContent').value = ''; // Clear input
        } else {
            messageBox.innerHTML = `<p class="text-red-500">${result.message}</p>`;
        }
    });
});

// Initialize: fetch messages
fetchMessages();
</script>

</body>
</html>
