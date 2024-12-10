<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">
<?php
$pageHeader = "Messages";
    if (file_exists('../includes/navbar.php')){
        include '../includes/navbar.php';
        } else {
            echo "<p class='text-red-500'>Error: File not found.</p>";
        }
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Pass session variables to JavaScript
echo "<script>
    const senderId = {$_SESSION['user_id']};
    const username = '" . htmlspecialchars($_SESSION['username'], ENT_QUOTES) . "';
</script>";
?>
    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Messages</h1>

        <div id="messages" class="space-y-4 max-h-80 overflow-y-auto border border-gray-300 p-4 rounded-lg bg-gray-50">
            <!-- Messages will be displayed here -->
        </div>

        <div class="mt-6">
            <label for="receiverSelect" class="block text-sm font-medium text-gray-700">Message send to:</label>
            <select id="receiverSelect" class="mt-2 block w-full px-4 py-2 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <!-- Dropdown options will be populated by JavaScript -->
            </select>
        </div>

        <textarea id="messageContent" placeholder="Type your message..." rows="4" 
            class="mt-4 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
        
        <button id="sendMessage" 
            class="mt-4 w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Send
        </button>

        <div id="messageBox" class="mt-4 text-center"></div>
    </div>

    <script>
        // Ensure the user is logged in
        // senderId and username are passed from PHP
        if (!senderId) {
            window.location.href = 'login.php'; // Redirect to login page
        }

        // Fetch and display users for the 'Send to' dropdown
        function fetchUsers() {
            fetch('../backend/fetch_users.php')
                .then(response => response.json())
                .then(users => {
                    const select = document.getElementById('receiverSelect');
                    select.innerHTML = '';
                    users.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.user_id;
                        option.textContent = user.username;
                        select.appendChild(option);
                    });
                });
        }

        // Fetch and display messages from the database
        function fetchMessages() {
            fetch('../backend/fetch_messages.php')
                .then(response => response.json())
                .then(messages => {
                    const messagesDiv = document.getElementById('messages');
                    messagesDiv.innerHTML = ''; // Clear any previous messages

                    messages.forEach(msg => {
                        const messageElement = document.createElement('div');
                        messageElement.className = 'p-3 rounded-lg shadow-sm bg-blue-50 border border-blue-200';

                        // Display the message content with the sender's or receiver's username
                        if (msg.sender_username === localStorage.getItem('username')) {
                            messageElement.textContent = `You: ${msg.content}`; // Sender message
                            messageElement.className += ' text-right bg-green-100';
                        } else {
                            messageElement.textContent = `${msg.sender_username}: ${msg.content}`; // Receiver message
                        }

                        messagesDiv.appendChild(messageElement);
                    });
                });
        }

        // Send a new message
        document.getElementById('sendMessage').addEventListener('click', () => {
            const content = document.getElementById('messageContent').value;
            const receiverId = document.getElementById('receiverSelect').value;
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
                    // Display success message
                    messageBox.innerHTML = `<p class="text-green-500">${result.message}</p>`;
                    fetchMessages(); // Refresh the messages
                    document.getElementById('messageContent').value = ''; // Clear the input
                } else {
                    // Display error message
                    messageBox.innerHTML = `<p class="text-red-500">${result.message}</p>`;
                }
            });
        });

        // Periodically fetch messages every 5 seconds
        setInterval(fetchMessages, 5000);

        // Initialize the page by fetching users and messages
        fetchUsers();
        fetchMessages();
    </script>
</body>
</html>
