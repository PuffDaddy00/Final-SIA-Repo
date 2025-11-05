<?php
header('Content-Type: application/json');

// Connect to DB
$conn = new mysqli("localhost", "root", "", "sites_db");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit;
}

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);

$title = $input['name'] ?? '';
$dueDate = $input['dueDate'] ?? '';
$priority = $input['priority'] ?? '';
$assignedTo = $input['assignedTo'] ?? [];
$subtasks = $input['subtasks'] ?? [];

if (!$title || !$dueDate || !$priority || empty($assignedTo)) {
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit;
}

// Insert into tasks table
$stmt = $conn->prepare("INSERT INTO tasks (name, due_date, priority, status) VALUES (?, ?, ?, ?)");
$status = "Pending";
$stmt->bind_param("ssss", $title, $dueDate, $priority, $status);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "error" => $stmt->error]);
    exit;
}

$taskId = $stmt->insert_id; // get last inserted task ID
$stmt->close();

// Insert subtasks
if (!empty($subtasks)) {
    $subStmt = $conn->prepare("INSERT INTO task_subtasks (task_id, subtask) VALUES (?, ?)");
    foreach ($subtasks as $subtask) {
        $subStmt->bind_param("is", $taskId, $subtask);
        $subStmt->execute();
    }
    $subStmt->close();
}

// Insert assigned members
if (!empty($assignedTo)) {
    $assignStmt = $conn->prepare("INSERT INTO task_assignments (task_id, member_id) VALUES (?, ?)");
    foreach ($assignedTo as $member) {
        $assignStmt->bind_param("is", $taskId, $member);
        $assignStmt->execute();
    }
    $assignStmt->close();
}

$conn->close();
echo json_encode(["success" => true]);
?>
