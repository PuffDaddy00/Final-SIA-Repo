<?php
include 'db.php'; // Make sure this file connects to your database

$sql = "SELECT id, username, name, role, email, contact, profilePic FROM users"; // added role
$result = $conn->query($sql);

$members = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
            // ✅ Set default values for null/empty fields
            $row['name'] = !empty($row['name']) ? $row['name'] : "No Name";
            $row['username'] = !empty($row['username']) ? $row['username'] : "N/A";
            $row['role'] = !empty($row['role']) ? $row['role'] : "No Role"; // added role default
            $row['email'] = !empty($row['email']) ? $row['email'] : "No Email";
            $row['contact'] = !empty($row['contact']) ? $row['contact'] : "No Contact";

            // ✅ Set default profile picture if missing
            $row['profilePic'] = !empty($row['profilePic']) && file_exists("uploads/" . $row['profilePic'])
                ? "uploads/" . $row['profilePic']
                : "SITES LOGO.png";

            $members[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($members);

$conn->close();
?>
