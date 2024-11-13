<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chatbot</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="chat-header">
            <h2>👋 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <a href="logout.php" class="logout-btn">Sign Out</a>
        </div>
        <div class="chat-box" id="chat-box"></div>
        <div class="chat-input">
            <form id="chat-form">
                <input type="text" id="message" placeholder="Type your message here..." required>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>