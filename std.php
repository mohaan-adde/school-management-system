

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<?php
include './connection/conn.php';

$search_name = '';
$search_gender = '';

if (isset($_POST['search_name'])) {
    $search_name = $_POST['search_name'];
}

if (isset($_POST['search_gender'])) {
    $search_gender = $_POST['search_gender'];
}

$sql = "SELECT * FROM students WHERE name LIKE ? AND gender LIKE ?";
$stmt = $conn->prepare($sql);
$search_name = "%$search_name%";
$search_gender = "%$search_gender%";
$stmt->bind_param("ss", $search_name, $search_gender);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $enrolled_in = $_POST['enrolled_in'];

    $check_email = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo "<script>alert('Email is already in use!'); window.history.back();</script>";
        exit();
    }
    $check_email->close();

    $stmt = $conn->prepare("INSERT INTO students (name, birthdate, gender, address, phone, email, enrolled_in) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $birthdate, $gender, $address, $phone, $email, $enrolled_in);

    if ($stmt->execute()) {
        echo "<script>alert('Student added successfully!'); window.location.href='std.php';</script>";
    } else {
        echo "<script>alert('Error adding student!');</script>";
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_student'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $enrolled_in = $_POST['enrolled_in'];

    $check_email = $conn->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
    $check_email->bind_param("si", $email, $id);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo "<script>alert('Email is already in use by another student!');</script>";
        exit();
    }
    $check_email->close();

    $stmt = $conn->prepare("UPDATE students SET name = ?, birthdate = ?, gender = ?, address = ?, phone = ?, email = ?, enrolled_in = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $name, $birthdate, $gender, $address, $phone, $email, $enrolled_in, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Student updated successfully!'); window.location.href='std.php';</script>";
    } else {
        echo "<script>alert('Error updating student!');</script>";
    }
    $stmt->close();
}



// delete code 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student'])) {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        $id = $data['id'];

        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Student deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error deleting student!"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request!"]);
    }
    exit();
}

?>
    <div class="sidebar">
        <h2 class="dashboard-title">teachers</h2>
        <ul class="nav-links">
            <li><a href="https://gsu.edu.so/"><img src="logo.jpg" alt="GSU" height="120" width="180"></a></li>
            <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="std.php" class="active">👤 Students</a></li>
            <li><a href="Teacher.php">👤 Teachers</a></li>
            <li><a href="courses.php">📚 Courses</a></li>
            <li><a href="Enrollments.php">📝 Enrollments</a></li>
            <li><a href="users.php">👥 Users</a></li>
        </ul>
        <div class="logout">
            <p>Welcome, administrator</p>
            <button class="logout-btn">Logout</button>
        </div>
    </div>

    <div class="main-content">
        <h1 class="main-title">Students</h1>
        <div class="search-bar">
            <input type="text" placeholder="Search by student...">
            <input type="text" placeholder="Search by gender">
            <button class="search-btn">🔍 Search</button>
        </div>
        <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStudentModal">+ Add New Student</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                   
                    <th>Name</th>
                    <th>Birthdate</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Enrolled In</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
    <td>{$row['id']}</td>
    <td>{$row['name']}</td>
    <td>{$row['birthdate']}</td>
    <td>{$row['gender']}</td>
    <td>{$row['address']}</td>
    <td>{$row['phone']}</td>
    <td>{$row['email']}</td>
    <td>{$row['enrolled_in']}</td>
    <td>
        <button class='btn btn-warning btn-sm update-btn' 
                data-id='{$row['id']}' 
                data-name='{$row['name']}' 
                data-birthdate='{$row['birthdate']}' 
                data-gender='{$row['gender']}' 
                data-address='{$row['address']}' 
                data-phone='{$row['phone']}' 
                data-email='{$row['email']}' 
                data-enrolled_in='{$row['enrolled_in']}'>
            Update
        </button>
<!-- Delete Button -->
<td>
    <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id']}'>Delete</button>
</td>

    </td>
</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No students found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="std.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input type="date" name="birthdate" class="form-control" id="birthdate" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" class="form-control" id="gender" required>
                            <option value="" disabled selected>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" id="phone">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" required>
                    </div>
                    <!-- Enrolled In -->
                    <div class="mb-3">
                        <label for="enrolled_in" class="form-label">Enrolled In</label>
                        <select name="enrolled_in" class="form-control" id="enrolled_in" required>
                            <option value="" disabled selected>Select a course</option>
                            <option value="php">php</option>
                            <option value="html">Html and css</option>
                            <option value="graphics design">graphic design</option>
                            <?php
                            include './connection/conn.php';
                            $query = "SELECT course_name FROM courses";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['course_name']}'>{$row['course_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateStudentModal" tabindex="-1" aria-labelledby="updateStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStudentModalLabel">Update Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="std.php">
                    <input type="hidden" name="id" id="update_id">
                    <div class="mb-3">
                        <label for="update_name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="update_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_birthdate" class="form-label">Birthdate</label>
                        <input type="date" name="birthdate" id="update_birthdate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_gender" class="form-label">Gender</label>
                        <select name="gender" id="update_gender" class="form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="update_address" class="form-label">Address</label>
                        <textarea name="address" id="update_address" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="update_phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="update_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_email" class="form-label">Email</label>
                        <input type="email" name="email" id="update_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_enrolled_in" class="form-label">Enrolled In</label>
                        <input type="text" name="enrolled_in" id="update_enrolled_in" class="form-control" required>
                    </div>
                    <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".update-btn").forEach(button => {
            button.addEventListener("click", () => {
                const id = button.getAttribute("data-id");
                const name = button.getAttribute("data-name");
                const birthdate = button.getAttribute("data-birthdate");
                const gender = button.getAttribute("data-gender");
                const address = button.getAttribute("data-address");
                const phone = button.getAttribute("data-phone");
                const email = button.getAttribute("data-email");
                const enrolledIn = button.getAttribute("data-enrolled_in");

                document.getElementById("update_id").value = id;
                document.getElementById("update_name").value = name;
                document.getElementById("update_birthdate").value = birthdate;
                document.getElementById("update_gender").value = gender;
                document.getElementById("update_address").value = address;
                document.getElementById("update_phone").value = phone;
                document.getElementById("update_email").value = email;
                document.getElementById("update_enrolled_in").value = enrolledIn;

                const updateModal = new bootstrap.Modal(document.getElementById("updateStudentModal"));
                updateModal.show();
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", () => {
                const studentId = button.getAttribute("data-id");

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch("std.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                id: studentId,
                                delete_student: true
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Deleted!", data.message, "success");
                                button.closest("tr").remove();
                            } else {
                                Swal.fire("Error!", data.message, "error");
                            }
                        })
                        .catch(error => {
                            Swal.fire("Error!", "An error occurred while deleting the student.", "error");
                            console.error(error);
                        });
                    }
                });
            });
        });
    });
</script>

</body>
</html>
