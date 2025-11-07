<?php
session_start();
include 'db.php'; // database connection

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(["error" => "No active session"]);
    exit();
}

$username = $_SESSION['username'];

// Fetch logged-in user info
$sql = "SELECT id, username, name, role, email, contact, profilePic FROM users WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$userProfile = null;

if ($result && $row = $result->fetch_assoc()) {
    $userProfile = [
        'id' => $row['id'],
        'username' => $row['username'] ?: "N/A",
        'name' => $row['name'] ?: "No Name",
        'role' => $row['role'] ?: "No Role",
        'email' => $row['email'] ?: "No Email",
        'contact' => $row['contact'] ?: "No Contact",
        'profilePic' => (!empty($row['profilePic']) && file_exists("uploads/" . $row['profilePic']))
            ? "uploads/" . $row['profilePic']
            : "SITES LOGO.png"
    ];
}

header('Content-Type: application/json');
echo json_encode($userProfile);

$conn->close();
?>
