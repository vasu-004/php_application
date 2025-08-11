<?php
// Load saved messages
$messages = [];
if (file_exists('messages.txt')) {
    $messages = file('messages.txt', FILE_IGNORE_NEW_LINES);
}

// Save new message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']);
    file_put_contents('messages.txt', $message . PHP_EOL, FILE_APPEND);
    header("Location: index.php"); // Prevent form resubmission
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple PHP App</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { margin-bottom: 20px; }
        .message { background: #f2f2f2; padding: 10px; margin-bottom: 5px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>My Simple PHP App</h1>
    <form method="POST">
        <input type="text" name="message" placeholder="Type a message" required>
        <button type="submit">Save</button>
    </form>
    <h2>Messages</h2>
    <?php if ($messages): ?>
        <?php foreach ($messages as $msg): ?>
            <div class="message"><?= $msg ?></div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No messages yet.</p>
    <?php endif; ?>
</body>
</html>
