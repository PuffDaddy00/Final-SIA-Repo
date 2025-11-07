<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "sites_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//PLEASE DELETE AFTER THE TESTING PHASE IS OVER. THE LET ME IN ACCESS.
// 🟡 TEMPORARY SPECIAL ACCESS CODE
// Example: ?access=letmein123
if (isset($_GET['access']) && $_GET['access'] === 'letmein123') {
    $_SESSION['user_id'] = 1; // or an existing admin ID
    $_SESSION['username'] = 'admin';
    $_SESSION['role'] = 'Admin';
    header("Location: admin2.php");
    exit();
}

// Normal login flow
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Query user by username
$sql = "SELECT * FROM users WHERE BINARY username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {

        // ✅ Store essential user info in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // ✅ Redirect user by role
        if ($user['role'] === "Admin") {
            header("Location: admin2.php");
        } elseif ($user['role'] === "Leader") {
            header("Location: leader.php");
        } else {
            header("Location: member.php");
        }
        exit();

    } else {
        echo "<p style='color:red;'>Invalid username or password</p>";
    }
} else {
    echo "<p style='color:red;'>Invalid username or password</p>";
}

$conn->close();
?>
