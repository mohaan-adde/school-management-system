<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include('./connection/conn.php');  

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password']; 

    $query = "INSERT INTO users (username, email, role, password) VALUES ('$username', '$email', '$role', '$password')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'User added successfully!',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location = './users.php';
                    });
                });
              </script>";
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '" . mysqli_error($conn) . "',
                        confirmButtonText: 'OK'
                    });
                });
              </script>";
    }
}

$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<div class="sidebar">
    <h2 class="dashboard-title">Dashboard</h2>
    <ul class="nav-links">
        <li><a href="https://gsu.edu.so/"><img src="logo.jpg" alt="GSU" height="120" width="170"></a></li>
        <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="std.php">👤 Students</a></li>
        <li><a href="courses.php">📚 Courses</a></li>
        <li><a href="Teacher.php">👤 Teachers</a></li>
        <li><a href="Enrollments.php">📝 Enrollments</a></li>
        <li><a href="users.php">👥 Users</a></li>
    </ul>
    <div class="logout">
        <p>Welcome, administrator</p>
        <button class="logout-btn">Logout</button>
    </div>
</div> 

<div class="main-content">
    <h1 class="main-title">Users</h1>
    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">Add New User</button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['password'] . "</td>"; 
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>
                        <button class='btn btn-warning btn-sm update-btn' 
                        data-user_id='" . $row['user_id'] . "' 
                        data-username='" . $row['username'] . "' 
                        data-password='" . $row['password'] . "' 
                        data-email='" . $row['email'] . "' 
                        data-role='" . $row['role'] . "'>Update</button>
                        <a href='./delete/delete_user.php?user_id=" . $row['user_id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" name="role" class="form-control" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update User Modal -->
<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserModalLabel">Update User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="./update/update_user.php">
                    <input type="hidden" name="user_id" id="update_user_id">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" id="update_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" id="update_password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="update_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" name="role" id="update_role" class="form-control" required>
                    </div>
                    
                    <button type="submit" name="update" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('.update-btn').click(function() {
            $('#update_user_id').val($(this).data('user_id'));
            $('#update_username').val($(this).data('username'));
            $('#update_email').val($(this).data('email'));
            $('#update_role').val($(this).data('role'));
            $('#update_password').val($(this).data('password')); 
            $('#updateUserModal').modal('show');
        });
    });
</script>

</body>
</html>
