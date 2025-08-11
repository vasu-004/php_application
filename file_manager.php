<?php
$uploadDir = __DIR__ . '/uploads';

// Create uploads folder if not exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileName = basename($_FILES['file']['name']);
    $targetFile = $uploadDir . '/' . $fileName;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        echo "<p class='success'>File uploaded successfully!</p>";
    } else {
        echo "<p class='error'>Upload failed.</p>";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $fileToDelete = $uploadDir . '/' . basename($_GET['delete']);
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        echo "<p class='error'>File deleted!</p>";
    }
}

// Get files
$files = array_diff(scandir($uploadDir), ['.', '..']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP File Manager</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function searchFiles() {
            let filter = document.getElementById("search").value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        }
    </script>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>📂 PHP File Manager</h1>
        <div class="subtitle">Upload, search, download & delete — no database required</div>
    </div>

    <div class="card">
        <form class="upload-row" action="" method="post" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
        <div class="search-wrap">
            <input type="text" id="search" class="search-input" onkeyup="searchFiles()" placeholder="Type to search...">
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($files) === 0): ?>
                <tr><td colspan="2" class="empty">No files uploaded yet</td></tr>
            <?php else: ?>
                <?php foreach ($files as $file): ?>
                    <tr>
                        <td class="file-name"><?= htmlspecialchars($file) ?></td>
                        <td class="actions">
                            <a href="uploads/<?= urlencode($file) ?>" class="download" download>Download</a>
                            <a href="?delete=<?= urlencode($file) ?>" class="delete" onclick="return confirm('Delete this file?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
