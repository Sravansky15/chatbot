<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $message = $input['message'];
    $user_id = $_SESSION['user_id'];

    // Save user message
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $stmt->execute([$user_id, $message]);

    // Get bot response
    $bot_response = getBotResponse($pdo, $message);
    
    // Save bot response
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, message, is_bot) VALUES (?, ?, 1)");
    $stmt->execute([$user_id, $bot_response]);

    echo json_encode(['response' => $bot_response]);
}

function getBotResponse($pdo, $message) {
    $message = strtolower(trim($message));
    
    // Search for matching patterns in the database
    $stmt = $pdo->prepare("SELECT response FROM bot_responses WHERE ? LIKE CONCAT('%', pattern, '%') LIMIT 1");
    $stmt->execute([$message]);
    $result = $stmt->fetch();

    if ($result) {
        return $result['response'];
    }

    // Fallback responses if no pattern matches
    $fallback_responses = [
        "I'm not sure I understand. Could you rephrase that? 🤔",
        "Interesting! Tell me more about that. 💭",
        "I'm still learning! Could you try asking in a different way? 📚",
        "I'm not quite sure about that. Could you elaborate? 🤔",
        "That's an interesting point! Could you explain further? 💡"
    ];

    return $fallback_responses[array_rand($fallback_responses)];
}
?>