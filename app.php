<?php
// Create uploads directory if it doesn't exist
$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileName = basename($_FILES['file']['name']);
    $targetFile = $uploadDir . '/' . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        $message = "✅ File uploaded successfully!";
    } else {
        $message = "❌ Failed to upload file.";
    }
}

// Get list of uploaded files
$files = array_diff(scandir($uploadDir), array('.', '..'));
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP File Upload App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>📂 PHP File Upload Application</h2>

    <?php if (!empty($message)) echo "<p class='msg'>$message</p>"; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    <h3>Uploaded Files:</h3>
    <ul>
        <?php foreach ($files as $file): ?>
            <li><a href="uploads/<?php echo urlencode($file); ?>" target="_blank"><?php echo htmlspecialchars($file); ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
