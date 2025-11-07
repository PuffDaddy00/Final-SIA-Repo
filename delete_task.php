<?php
header('Content-Type: application/json');

// Connect to DB
$conn = new mysqli("localhost", "root", "", "sites_db");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["success" => false, "error" => "No task ID provided"]);
    exit;
}

$taskId = $data['id']; // e.g. "t17"

// ✅ Remove the 't' prefix if present
$taskId = preg_replace('/^t/', '', $taskId); 

// Optional: make sure it's numeric
if (!is_numeric($taskId)) {
    echo json_encode(["success" => false, "error" => "Invalid task ID format"]);
    exit;
}

// Delete subtasks
$conn->query("DELETE FROM task_subtasks WHERE task_id = '$taskId'");
// Delete assignments
$conn->query("DELETE FROM task_assignments WHERE task_id = '$taskId'");

// Delete main task
if ($conn->query("DELETE FROM tasks WHERE id = '$taskId'")) {
    echo json_encode(["success" => true, "message" => "Task deleted successfully"]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>
