<?php
$host = "localhost";     // usually localhost
$user = "root";          // your database username
$pass = "";              // your database password
$db   = "sites_db";      // your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>
