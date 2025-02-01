<?php
session_start();

// Admin credentials
$admin_id = "kaushal";
$admin_password = "Rahu@123";

// Handle admin login
if (isset($_POST['login'])) {
    if ($_POST['admin_id'] == $admin_id && $_POST['password'] == $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = "❌ Invalid credentials!";
    }
}

// Handle file upload
if (isset($_POST['upload'])) {
    $username = $_POST['username'];
    $targetDir = "uploads/";
    $uploadSuccess = false;

    foreach ($_FILES["files"]["name"] as $key => $fileName) {
        $targetFilePath = $targetDir . $username . "_" . basename($fileName);
        if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
            $uploadSuccess = true;
        }
    }

    $message = $uploadSuccess ? "✅ Files uploaded successfully!" : "❌ Error uploading files!";
}

// Handle admin logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Submission</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>📂 Secure Document Submission</h2>

    <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <?php if (!isset($_SESSION['admin_logged_in'])): ?>

        <!-- User Upload Form -->
        <div class="card">
            <h3>Upload Your Documents</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="text" name="username" placeholder="Enter your name" required>
                <input type="file" name="files[]" multiple required>
                <button type="submit" name="upload">Upload</button>
            </form>
        </div>

        <!-- Admin Login Form -->
        <div class="card">
            <h3>Admin Login</h3>
            <form action="" method="post">
                <input type="text" name="admin_id" placeholder="Admin ID" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        </div>

    <?php else: ?>

        <!-- Admin Panel -->
        <div class="card">
            <h3>📄 Uploaded Documents</h3>
            <div class="file-list">
                <?php
                $files = scandir("uploads/");
                foreach ($files as $file) {
                    if ($file !== "." && $file !== "..") {
                        echo "<a href='uploads/$file' download>📎 $file</a><br>";
                    }
                }
                ?>
            </div>
            <form action="" method="post">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>

    <?php endif; ?>
</div>

</body>
</html>
