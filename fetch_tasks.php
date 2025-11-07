<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "sites_db");
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// Get all tasks
$tasksResult = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
$tasks = [];

while ($task = $tasksResult->fetch_assoc()) {
    $taskId = $task['id'];

    // Get subtasks
    $subtasksResult = $conn->query("SELECT subtask FROM task_subtasks WHERE task_id = $taskId");
    $subtasks = [];
    while ($row = $subtasksResult->fetch_assoc()) {
        $subtasks[] = $row['subtask'];
    }

    // Get assigned members
    $assignResult = $conn->query("SELECT member_id FROM task_assignments WHERE task_id = $taskId");
    $assignedTo = [];
    while ($row = $assignResult->fetch_assoc()) {
        $assignedTo[] = $row['member_id'];
    }

    $tasks[] = [
        "id" => "t" . $taskId,
        "name" => $task['name'],
        "dueDate" => $task['due_date'],
        "priority" => $task['priority'],
        "status" => $task['status'],
        "subtasks" => $subtasks,
        "assignedTo" => $assignedTo
    ];
}

echo json_encode($tasks);
$conn->close();
?>
