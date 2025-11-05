<?php
session_start();

// Check if user is logged in and is Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.html");
    exit();
}


if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'UserDeleted') {
        echo "<script>
                alert('✅ User deleted successfully!');
                // Remove the query parameter from URL so alert won't appear on refresh
                window.history.replaceState({}, document.title, window.location.pathname);
              </script>";
    } elseif ($_GET['msg'] === 'UserAdded') {
        echo "<script>
                alert('✅ New user added successfully!');
                window.history.replaceState({}, document.title, window.location.pathname);
              </script>";
    }
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SITES Admin Dashboard</title>
    <!-- Link to the Inter font for a clean, modern look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Link to the external CSS file containing all styles -->
    <link rel="stylesheet" href="Admin.css">
    
</head>
<body>

    <div id="app">

        <!-- Header Panel -->
        <header class="header-panel">
            <!-- Left Side: Logo and Title -->
            <div class="header-logo-area">
                <h1 class="text-sites-primary">
                    Admin Dashboard
                </h1>
            </div>

            <!-- Right Side: Log Out Button -->
            <div class="flex items-center">
                <button id="logoutButton" class="logout-button" onclick="window.location='logout.php'">Log Out</button>

            </div>
        </header>

        <!-- Main Content Grid -->
        <main class="main-grid">

            <!-- Left/Primary Column: Add New Account Form (1/3 wide on desktop) -->
            <section class="card form-section">
                <h2>
                    Add New User Account
                </h2>
                <form id="addUserForm" class="form-container" method="POST" action="add_user.php" enctype="multipart/form-data">

                  <div class="form-group profile-pic-group">
  <label for="profilePic">Profile Picture</label>
  <img src="SITES LOGO.png"
       alt="Profile preview"
       id="addProfilePicPreview"
       onclick="document.getElementById('profilePic').click();"
       style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid #ccc;cursor:pointer;">
       
  <input type="file"
         id="profilePic"
         name="profilePic"
         accept="image/*"
         class="profile-pic-input"
         style="display:none;"
         >
         
  <label for="profilePic" class="file-label">Choose Image</label>
</div>

                   <!-- Username Input -->
                   <div class="form-group">
                        <label for="newUsername">Username</label>
                        <input type="text" id="newUsername" name="newUsername" required placeholder="e.g., JohnDoe123">
                    </div>

                    <!-- Name Input -->
                         <div class="form-group">
                         <label for="newName">Name</label>
                         <input type="text" id="newName" name="newName" required placeholder="e.g., John Doe">
                         </div>

                    <!-- Email Input -->
                         <div class="form-group">
                         <label for="newEmail">Email</label>
                         <input type="email" id="newEmail" name="newEmail" required placeholder="e.g., john@example.com">
                         </div>

                    <!-- Contact Input -->
                         <div class="form-group">
                         <label for="newContact">Contact Number</label>
                         <input type="tel" id="newContact" name="newContact" required placeholder="e.g., 09123456789">
                         </div>

                    <!-- Password Input -->
                         <div class="form-group">
                         <label for="newPassword">Initial Password</label>
                         <input type="text" id="newPassword" name="newPassword" required placeholder="e.g., SecurePassword1">
                         </div>

                     <!-- Role Dropdown -->
                         <div class="form-group">
                         <label for="newRole">Assign Role</label>
                         <select id="newRole" name="newRole" required>
                         <option value="Member">Member</option>
                         <option value="Leader">Leader</option>
                         </select>
                         </div>

                         <!-- Submit -->
                         <button type="submit" class="submit-button bg-sites-primary">
                          Add Account
                         </button>
                         </form>


                <!-- Status Message Area (for adding users via PHP response) -->
                <div id="addStatusMessage" style="min-height: 20px;">
                    <!-- PHP success/error messages will be displayed here -->
                </div>
            </section>

            <!-- Right/Secondary Column: User Management Table (2/3 wide on desktop) -->
            <section class="card table-section">
                <h2>
                    Manage All SITES Users
                </h2>

                <!-- Status Message Area (for table updates) -->
                <div id="listStatusMessage" style="min-height: 20px;">
                    <!-- Messages appear here -->
                </div>

                <!-- User Table Container -->
                <div class="table-wrapper">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th class="center">Password</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <?php
                               $conn = new mysqli("localhost", "root", "", "sites_db");
                               $result = $conn->query("SELECT * FROM users");

                               while ($row = $result->fetch_assoc()) {
                               echo "<tr>
                               <td>{$row['username']}</td>
                               <td>{$row['name']}</td>
                               <td>{$row['role']}</td>
                               <td class='center'>••••••</td>
                               <td>
                               <a href='edit_user.php?id={$row['id']}'>Edit</a> | 
                               <a href='delete_user.php?id={$row['id']}' onclick=\"return confirm('Are you sure?');\">Delete</a>
                               </td>
                               </tr>";
                               }
                            ?>

                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- The actual functional script is omitted for design preview -->
</body>
</html>