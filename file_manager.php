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
        echo "<p style='color:green;'>File uploaded successfully!</p>";
    } else {
        echo "<p style='color:red;'>Upload failed.</p>";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $fileToDelete = $uploadDir . '/' . basename($_GET['delete']);
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        echo "<p style='color:red;'>File deleted!</p>";
    }
}

// Get files
$files = array_diff(scandir($uploadDir), ['.', '..']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP File Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
        input[type="text"] { padding: 5px; width: 300px; }
    </style>
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
    <h1>📂 PHP File Manager</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    <h3>Search Files</h3>
    <input type="text" id="search" onkeyup="searchFiles()" placeholder="Type to search...">

    <h3>Uploaded Files</h3>
    <table>
        <thead>
            <tr>
                <th>File Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($files as $file): ?>
            <tr>
                <td><?= htmlspecialchars($file) ?></td>
                <td>
                    <a href="uploads/<?= urlencode($file) ?>" download>Download</a> | 
                    <a href="?delete=<?= urlencode($file) ?>" onclick="return confirm('Delete this file?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
