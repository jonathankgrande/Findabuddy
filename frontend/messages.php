<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<?php
$pageHeader = "Messages";
    if (file_exists('../includes/navbar.php')){
        include '../includes/navbar.php';
        } else {
            echo "<p> Error: File not found. </p>";
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


    <h1>Messages(this page need css stylish)</h1><br><br>

    <div id="messages">
        <!-- Messages will be displayed here -->
    </div>
    <br>
    <div>
        <h3>Message send to: </h>
        <select id="receiverSelect">
            <!-- Dropdown options will be populated by JavaScript -->
        </select>
    </div>
    <br>
    <textarea id="messageContent" placeholder="Type your message..." rows="4" style="width: 100%;"></textarea>
    <button id="sendMessage">Send</button>

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
                    messageElement.className = 'message';

                    // Display the message content with the sender's or receiver's username
                    if (msg.sender_username === localStorage.getItem('username')) {
                        messageElement.textContent = `${msg.sender_username}: ${msg.content}`; // Sender message
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
                messageBox.innerHTML = `<p style="color: green;">${result.message}</p>`;
                fetchMessages(); // Refresh the messages
                document.getElementById('messageContent').value = ''; // Clear the input
            } else {
                // Display error message
                messageBox.innerHTML = `<p style="color: red;">${result.message}</p>`;
            }
        });
    });

    // Periodically fetch messages every 5 seconds
    setInterval(fetchMessages, 5000);

    // Initialize the page by fetching users and messages
    fetchUsers();
    fetchMessages();
</script>

<div id="messageBox"></div>
    </script>
</body>
</html>

