<?php
session_start();

// ✅ Ensure only Admin can add users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sites_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['newUsername'] ?? '';
    $name = $_POST['newName'] ?? '';
    $email = $_POST['newEmail'] ?? '';
    $contact = $_POST['newContact'] ?? '';
    $password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
    $role = $_POST['newRole'] ?? '';

    // ✅ Check if username already exists before inserting
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('⚠️ Duplicate username found! Please choose another.'); window.history.back();</script>";
        exit();
    }

    // ✅ Handle profile picture upload
    $profilePic = null;
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES["profilePic"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $targetFile)) {
            $profilePic = $fileName;
        }
    }

    // ✅ Insert new user if no duplicates
    $stmt = $conn->prepare("INSERT INTO users (username, name, email, contact, password, role, profilePic) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $name, $email, $contact, $password, $role, $profilePic);

    if ($stmt->execute()) {
        header("Location: admin2.php?msg=UserAdded");
        exit();
    } else {
        // Catch other SQL errors (like missing fields)
        echo "<script>alert('❌ Database error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }

    $stmt->close();
    $check->close();
    $conn->close();
}
?>
