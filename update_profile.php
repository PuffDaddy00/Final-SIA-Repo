<?php
session_start();
header('Content-Type: application/json');

// Enable errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$conn = new mysqli("localhost", "root", "", "sites_db");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);
$field = $data['field'] ?? '';
$value = $data['value'] ?? '';

// Only allow email or contact
$allowedFields = ['email', 'contact'];
if (!in_array($field, $allowedFields)) {
    echo json_encode(['success' => false, 'message' => 'Invalid field']);
    exit;
}

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

// Prepare update statement
$stmt = $conn->prepare("UPDATE users SET $field = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}
$stmt->bind_param("si", $value, $userId);

// Execute
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'field' => $field, 'newValue' => $value]);
} else {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
