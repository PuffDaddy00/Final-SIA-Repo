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
    <title>Admin Dashboard</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- External CSS -->
    <link rel="stylesheet" href="Admin.css">
</head>
<body>

    <div id="dashboard-container">
        <header>
            <h1>Admin dashboard</h1>
            <button class="logout-btn" onclick="window.location='logout.php'">Log Out</button>
        </header>

        <div id="status-message" class="status-message"></div>

        <main>
            <section id="form-section" class="card">
                <h2>Add new user account</h2>
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
         onchange="previewProfilePic(event)">
         
  <label for="profilePic" class="file-label">Choose Image</label>
</div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="newUsername" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="newName" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="newEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="contact">Contact number</label>
                        <input type="tel" id="contact" name="newContact" required>
                    </div>
                    <div class="form-group">
                        <label for="password">password</label>
                        <input type="password" id="password" name="newPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Assign role</label>
                        <select id="role" name="newRole">
                            <option value="Member">Member</option>
                            <option value="Admin">Admin</option>
                            <option value="Leader">Leader</option>
                        </select>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="submit-btn" id="submit-btn">ADD MEMBER</button>
                    </div>
                </form>
            </section>
            
            <section id="table-section" class="card">
                <h2>Manage all SITES members</h2>
                <div class="table-wrapper">
                    <table id="members-table">
                        <thead>
                        <tr>
                        <th>Profile</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Password</th>
                        <th>Action</th>
                        </tr>
                        </thead>

                       <tbody id="members-table-body">
<?php
$conn = new mysqli("localhost", "root", "", "sites_db");

// ✅ Connection check (same)
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// 🟡 Changed: stored SQL in variable for clarity
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if (!$result) {
    die("❌ Query failed: " . $conn->error);
}

// 🟡 Moved: connection now closes AFTER the loop, not before
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // 🟡 Added file_exists() check to prevent broken image
        $profilePic = !empty($row['profilePic']) && file_exists("uploads/" . $row['profilePic'])
            ? "uploads/" . $row['profilePic']
            : "SITES LOGO.png";

        echo "
        <tr>
             
            <td><img src='{$profilePic}' alt='Profile' width='40' height='40' style='border-radius:50%; object-fit:cover;'></td>
            <td>{$row['username']}</td>
            <td>{$row['name']}</td>
            <!-- 🟡 Removed email/contact mismatch, fixed table columns -->
            <td>{$row['role']}</td>
            <td class='center'>••••••</td>
            <td>
                <a href='edit_user.php?id={$row['id']}'>Edit</a> | 
                <a href='delete_user.php?id={$row['id']}' onclick=\"return confirm('Are you sure?');\">Delete</a>
            </td>
        </tr>";
    }
} else {
    // 🟡 Added fallback when no users are found
    echo "<tr><td colspan='6'>No members found</td></tr>";
}

// 🟡 Connection now closed after loop
$conn->close();
?>
</tbody>

                    </table>
                </div>
            </section>
        </main>
    </div>

   



    <script>
function previewProfilePic(event) {
  const file = event.target.files[0];
  if (!file) return; // no file selected
  const reader = new FileReader();
  reader.onload = function(e) {
    document.getElementById('addProfilePicPreview').src = e.target.result;
  }
  reader.readAsDataURL(file);
}




document.addEventListener('DOMContentLoaded', function() {
    const addUserForm = document.getElementById('add-user-form');
    const tableBody = document.getElementById('members-table-body');
    const statusMessage = document.getElementById('status-message');
    
    // Add User form profile pic elements
    const addProfilePicInput = document.getElementById('profilePic');
    const addProfilePicPreview = document.getElementById('addProfilePicPreview');

    // Edit Modal elements
    const editModal = document.getElementById('editModal');
    const editUserForm = document.getElementById('edit-user-form');
    const closeEditModalBtn = editModal.querySelector('.close-btn');
    const cancelEditModalBtn = editModal.querySelector('.modal-btn.cancel');
    const editProfilePicInput = document.getElementById('editProfilePic');
    const editProfilePicPreview = document.getElementById('editProfilePicPreview');
    let rowToEdit = null;

    // Delete Modal elements
    const deleteModal = document.getElementById('deleteModal');
    const confirmDeleteBtn = deleteModal.querySelector('.modal-btn.confirm');
    const cancelDeleteBtn = deleteModal.querySelector('.modal-btn.cancel');
    let rowToDelete = null;

    // Image Preview Modal elements
    const imagePreviewModal = document.getElementById('imagePreviewModal');
    const modalImage = document.getElementById('modalImage');
    const closeImagePreviewBtn = imagePreviewModal.querySelector('.close-btn');

    // --- HELPER FUNCTIONS ---
    
    const showStatus = (message, type = 'success') => {
        statusMessage.textContent = message;
        statusMessage.className = `status-message ${type}`;
        statusMessage.style.display = 'block';
        setTimeout(() => { statusMessage.style.display = 'none'; }, 3000);
    };

    const handleImagePreview = (input, preview) => {
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => { preview.src = e.target.result; }
                reader.readAsDataURL(this.files[0]);
            }
        });
    };

    // New function to handle rendering a table row to reduce repetition
    const renderTableRow = (row, data) => {
        row.innerHTML = `
            <td><img src="${data.profilePicSrc}" alt="${data.name}" class="profile-pic-table"></td>
            <td>${data.username}</td>
            <td>${data.name}</td>
            <td>${data.role}</td>
            <td>****</td>
            <td class="action-links"><a class="edit-btn">Edit</a> <a class="delete-btn">Delete</a></td>
        `;
    };
    
    // --- MODAL FUNCTIONS ---
    const openImagePreviewModal = (src) => {
        modalImage.src = src;
        imagePreviewModal.style.display = 'flex';
    };

    const closeImagePreviewModal = () => {
        imagePreviewModal.style.display = 'none';
    };

    const openEditModal = (row) => {
        rowToEdit = row;
        const cells = row.querySelectorAll('td');
        
        editUserForm.querySelector('#editUserId').value = row.dataset.id;
        editProfilePicPreview.src = cells[0].querySelector('img').src;
        editUserForm.querySelector('#editUsername').value = cells[1].textContent;
        editUserForm.querySelector('#editName').value = cells[2].textContent;
        editUserForm.querySelector('#editRole').value = cells[3].textContent;
        editUserForm.querySelector('#editPassword').value = ''; 
        editUserForm.querySelector('#confirmEditPassword').value = ''; 
        editModal.style.display = 'flex';
    };

    const closeEditModal = () => {
        editModal.style.display = 'none';
        rowToEdit = null;
    };
    
    const openDeleteModal = (row) => {
        rowToDelete = row;
        deleteModal.style.display = 'flex';
    };

    const closeDeleteModal = () => {
        deleteModal.style.display = 'none';
        rowToDelete = null;
    };

    // --- EVENT LISTENERS ---

    handleImagePreview(addProfilePicInput, addProfilePicPreview);
    handleImagePreview(editProfilePicInput, editProfilePicPreview);

    tableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-btn')) openEditModal(e.target.closest('tr'));
        if (e.target.classList.contains('delete-btn')) openDeleteModal(e.target.closest('tr'));
        if (e.target.classList.contains('profile-pic-table')) openImagePreviewModal(e.target.src);
    });

    addUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(addUserForm);
        const userData = {
            username: formData.get('username'),
            name: formData.get('name'),
            role: formData.get('role'),
            profilePicSrc: addProfilePicPreview.src
        };
        
        const newRow = tableBody.insertRow();
        newRow.dataset.id = Date.now();
        renderTableRow(newRow, userData);

        showStatus('User added successfully!');
        addUserForm.reset();
        addProfilePicPreview.src = 'https://placehold.co/100x100/EFEFEF/AAAAAA?text=PREVIEW';
    });

    editUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(editUserForm);
        const newPassword = formData.get('password');
        const confirmPassword = formData.get('confirmPassword');

        if (newPassword !== confirmPassword) {
            showStatus('Passwords do not match.', 'error');
            return;
        }

        if (rowToEdit) {
            const userData = {
                username: formData.get('username'),
                name: formData.get('name'),
                role: formData.get('role'),
                profilePicSrc: editProfilePicPreview.src
            };
            renderTableRow(rowToEdit, userData);
        }
        showStatus('User updated successfully!');
        closeEditModal();
    });

    confirmDeleteBtn.addEventListener('click', () => {
        if (rowToDelete) {
            rowToDelete.remove();
            showStatus('User deleted successfully.');
        }
        closeDeleteModal();
    });

    // Listeners to close modals
    closeEditModalBtn.addEventListener('click', closeEditModal);
    cancelEditModalBtn.addEventListener('click', closeEditModal);
    cancelDeleteBtn.addEventListener('click', closeDeleteModal);
    closeImagePreviewBtn.addEventListener('click', closeImagePreviewModal);

    window.addEventListener('click', (e) => {
        if (e.target === deleteModal) closeDeleteModal();
        if (e.target === editModal) closeEditModal();
        if (e.target === imagePreviewModal) closeImagePreviewModal();
    });
});



</script>
    
</body>
</html>

