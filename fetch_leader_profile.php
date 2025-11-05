<?php
session_start();
include 'db.php'; // database connection

// ✅ Check if user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(["error" => "No active session"]);
    exit();
}

$username = $_SESSION['username']; // get logged-in user

// ✅ Fetch only that user’s info
$sql = "SELECT id, username, name, role, email, contact, profilePic FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$userProfile = null;

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Set default values
    $row['name'] = $row['name'] ?: "No Name";
    $row['username'] = $row['username'] ?: "N/A";
    $row['role'] = $row['role'] ?: "No Role";
    $row['email'] = $row['email'] ?: "No Email";
    $row['contact'] = $row['contact'] ?: "No Contact";

    // Default profile pic
    $row['profilePic'] = (!empty($row['profilePic']) && file_exists("uploads/" . $row['profilePic']))
        ? "uploads/" . $row['profilePic']
        : "SITES LOGO.png";

    $userProfile = $row;
}

header('Content-Type: application/json');
echo json_encode($userProfile);

$conn->close();
?>
