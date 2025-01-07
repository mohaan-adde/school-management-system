

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    table{
        overflow: scroll;
    }
     table th {
    white-space: nowrap; 
    min-width: 150px; 
}
</style>
<body>

<?php
include './connection/conn.php';

$search_name = isset($_POST['search_name']) ? $_POST['search_name'] : '';
$search_department = isset($_POST['search_department']) ? $_POST['search_department'] : '';

if (isset($_POST['add_teacher'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $department = $_POST['department'];
    $hire_date = $_POST['hire_date'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $qualifications = $_POST['qualifications'];

    $insert_query = "INSERT INTO teacher (First_Name, Last_Name, Email, Phone_Number, Department, Hire_Date, Position, Salary, Qualifications) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param(
        "sssssssis",
        $first_name,
        $last_name,
        $email,
        $phone_number,
        $department,
        $hire_date,
        $position,
        $salary,
        $qualifications
    );

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Teacher Added',
                text: 'The teacher was successfully added!'
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add the teacher. Please try again.'
            });
        </script>";
    }
    $stmt->close();
}

$sql = "SELECT * FROM teacher WHERE First_Name LIKE ? AND Department LIKE ?";
$stmt = $conn->prepare($sql);
$search_name = "%$search_name%";
$search_department = "%$search_department%";
$stmt->bind_param("ss", $search_name, $search_department);
$stmt->execute();
$result = $stmt->get_result();


if (isset($_POST['update_teacher'])) {
    $teacher_id = $_POST['update_id'];
    $first_name = $_POST['update_first_name'];
    $last_name = $_POST['update_last_name'];
    $email = $_POST['update_email'];
    $phone_number = $_POST['update_phone_number'];
    $department = $_POST['update_department'];
    $hire_date = $_POST['update_hire_date'];
    $position = $_POST['update_position'];
    $salary = $_POST['update_salary'];
    $qualifications = $_POST['update_qualifications'];

    $update_query = "UPDATE teacher 
                     SET First_Name = ?, Last_Name = ?, Email = ?, Phone_Number = ?, Department = ?, Hire_Date = ?, Position = ?, Salary = ?, Qualifications = ? 
                     WHERE Teacher_ID = ?";

    $stmt = $conn->prepare($update_query);
    $stmt->bind_param(
        "sssssssisi",
        $first_name,
        $last_name,
        $email,
        $phone_number,
        $department,
        $hire_date,
        $position,
        $salary,
        $qualifications,
        $teacher_id
    );

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Teacher Updated',
                text: 'The teacher details were successfully updated!'
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update the teacher. Please try again.'
            });
        </script>";
    }
    $stmt->close();
}

// delete code 

if (isset($_POST['delete_teacher'])) {
    $teacher_id = $_POST['delete_id'];

    $delete_query = "DELETE FROM teacher WHERE Teacher_ID = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $teacher_id);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Teacher Deleted',
                text: 'The teacher was successfully deleted!'
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to delete the teacher. Please try again.'
            });
        </script>";
    }
    $stmt->close();
}

?>

    <div class="sidebar">
        <h2 class="dashboard-title">Teachers</h2>
        <ul class="nav-links">
            <li><a href="https://gsu.edu.so/"><img src="logo.jpg" alt="GSU" height="120" width="180"></a></li>
            <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="teachers.php" class="active">👨‍🏫 Teachers</a></li>
            <li><a href="courses.php">📚 Courses</a></li>
            <li><a href="enrollments.php">📝 Enrollments</a></li>
            <li><a href="users.php">👥 Users</a></li>
        </ul>
        <div class="logout">
            <p>Welcome, administrator</p>
            <button class="logout-btn">Logout</button>
        </div>
    </div>

    <div class="main-content">
        <h1 class="main-title">Teachers</h1>
        <div class="search-bar">
            <form action="" method="POST" class="d-flex">
            <input type="text" name="search_name" class="form-control me-2" placeholder="Search by name..." value="<?php echo isset($_POST['search_name']) ? htmlspecialchars($_POST['search_name']) : ''; ?>">
<input type="text" name="search_department" class="form-control me-2" placeholder="Search by department..." value="<?php echo isset($_POST['search_department']) ? htmlspecialchars($_POST['search_department']) : ''; ?>">
                <button type="submit" class="btn btn-primary">🔍 Search</button>
            </form>
        </div>
        <div class="d-flex justify-content-between my-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTeacherModal">+ Add New Teacher</button>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 10%;">F.Name</th>
                    <th style="width: 10%;">L.Name</th>
                    <th style="width: 15%;">Email</th>
                    <th style="width: 10%;">Phone</th>
                    <th style="width: 10%;">Department</th>
                    <th style="width: 10%;">H.Date</th>
                    <th style="width: 10%;">Position</th>
                    <th style="width: 10%;">Salary</th>
                    <th style="width: 15%;">Qualifications</th>
                    <th style="width: 10%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['Teacher_ID']}</td>
                            <td>{$row['First_Name']}</td>
                            <td>{$row['Last_Name']}</td>
                            <td>{$row['Email']}</td>
                            <td>{$row['Phone_Number']}</td>
                            <td>{$row['Department']}</td>
                            <td>{$row['Hire_Date']}</td>
                            <td>{$row['Position']}</td>
                            <td>{$row['Salary']}</td>
                            <td>{$row['Qualifications']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm update-btn' 
                                        data-id='{$row['Teacher_ID']}' 
                                        data-first_name='{$row['First_Name']}'
                                        data-last_name='{$row['Last_Name']}' 
                                        data-email='{$row['Email']}'
                                        data-phone='{$row['Phone_Number']}'
                                        data-department='{$row['Department']}'
                                        data-hire_date='{$row['Hire_Date']}'
                                        data-position='{$row['Position']}'
                                        data-salary='{$row['Salary']}'
                                        data-qualifications='{$row['Qualifications']}'>
                                    Update
                                </button>
                                <button class='btn btn-danger btn-sm delete-btn' 
        data-id='{$row['Teacher_ID']}'>
    Delete
</button>


                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No teachers found</td></tr>";
                }
                
                // $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTeacherModalLabel">Add New Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="Teacher.php" method="POST">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" id="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" id="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" id="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" name="department" class="form-control" id="department" required>
                        </div>
                        <div class="mb-3">
                            <label for="hire_date" class="form-label">Hire Date</label>
                            <input type="date" name="hire_date" class="form-control" id="hire_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" name="position" class="form-control" id="position" required>
                        </div>
                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" name="salary" class="form-control" id="salary" required>
                        </div>
                        <div class="mb-3">
                            <label for="qualifications" class="form-label">Qualifications</label>
                            <textarea name="qualifications" id="qualifications" class="form-control" required></textarea>
                        </div>
                        <button type="submit" name="add_teacher" class="btn btn-primary">Add Teacher</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- update model -->
<div class="modal fade" id="updateTeacherModal" tabindex="-1" aria-labelledby="updateTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateTeacherModalLabel">Update Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <input type="hidden" name="update_id" id="update_id">
                    <div class="mb-3">
                        <label for="update_first_name" class="form-label">First Name</label>
                        <input type="text" name="update_first_name" id="update_first_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_last_name" class="form-label">Last Name</label>
                        <input type="text" name="update_last_name" id="update_last_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_email" class="form-label">Email</label>
                        <input type="email" name="update_email" id="update_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_phone_number" class="form-label">Phone Number</label>
                        <input type="text" name="update_phone_number" id="update_phone_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_department" class="form-label">Department</label>
                        <input type="text" name="update_department" id="update_department" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_hire_date" class="form-label">Hire Date</label>
                        <input type="date" name="update_hire_date" id="update_hire_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_position" class="form-label">Position</label>
                        <input type="text" name="update_position" id="update_position" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_salary" class="form-label">Salary</label>
                        <input type="number" name="update_salary" id="update_salary" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_qualifications" class="form-label">Qualifications</label>
                        <textarea name="update_qualifications" id="update_qualifications" class="form-control" required></textarea>
                    </div>
                    <button type="submit" name="update_teacher" class="btn btn-primary">Update Teacher</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- delete code  -->
<div class="modal fade" id="deleteTeacherModal" tabindex="-1" aria-labelledby="deleteTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTeacherModalLabel">Delete Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this teacher?</p>
                <form action="" method="POST">
                    <input type="hidden" name="delete_id" id="delete_id">
                    <button type="submit" name="delete_teacher" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const updateButtons = document.querySelectorAll('.update-btn');

    updateButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const firstName = this.getAttribute('data-first_name');
            const lastName = this.getAttribute('data-last_name');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const department = this.getAttribute('data-department');
            const hireDate = this.getAttribute('data-hire_date');
            const position = this.getAttribute('data-position');
            const salary = this.getAttribute('data-salary');
            const qualifications = this.getAttribute('data-qualifications');

            document.getElementById('update_id').value = id;
            document.getElementById('update_first_name').value = firstName;
            document.getElementById('update_last_name').value = lastName;
            document.getElementById('update_email').value = email;
            document.getElementById('update_phone_number').value = phone;
            document.getElementById('update_department').value = department;
            document.getElementById('update_hire_date').value = hireDate;
            document.getElementById('update_position').value = position;
            document.getElementById('update_salary').value = salary;
            document.getElementById('update_qualifications').value = qualifications;

            const updateModal = new bootstrap.Modal(document.getElementById('updateTeacherModal'));
            updateModal.show();
        });
    });
});

    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_id').value = id;

                const deleteModal = new bootstrap.Modal(document.getElementById('deleteTeacherModal'));
                deleteModal.show();
            });
        });
    });

</script>

</body>
</html>
