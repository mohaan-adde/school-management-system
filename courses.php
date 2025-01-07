
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<?php
include('.//connection/conn.php');  

if (isset($_POST['submit'])) {
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];

    $query = "INSERT INTO courses (course_name, description) VALUES ('$course_name', '$description')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Course added successfully!',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location = './courses.php';
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

$query = "SELECT * FROM courses";
$result = mysqli_query($conn, $query);
?>

    <div class="sidebar">
        <h2 class="dashboard-title">courses</h2>
        <ul class="nav-links">
            <li><a href="https://gsu.edu.so/"><img src="logo.jpg" alt="GSU" height="120" width="180"></a></li>
            <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="std.php" class="active">👤 Students</a></li>
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
        <h1 class="main-title">courses</h1>
        <div class="search-bar">
            <input type="text" placeholder="Search by student...">
            <input type="text" placeholder="Search by gender">
            <button class="search-btn">🔍 Search</button>
        </div>
        <!-- <button class="add-btn btn-success" data-bs-toggle="modal" data-bs-target="#addStudentModal">+ Add New Student</button> -->
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add New Course</button>
        </div>
        <!-- <button class="add-btn">+ Add New Student</button> -->

        <table>
            <thead>
                <tr>
                <th>ID</th>
                        <th>Course Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['course_id'] . "</td>";
                        echo "<td>" . $row['course_name'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>
                                <button class='btn btn-warning btn-sm update-btn' 
                                data-course_id='" . $row['course_id'] . "' 
                                data-course_name='" . $row['course_name'] . "' 
                                data-description='" . $row['description'] . "'>Update</button>
                                <a href='./delete/delete_course.php?course_id=" . $row['course_id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
        </table>
    </div>

  <!-- Add Course Modal -->
  <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" name="course_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Add Course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Course Modal -->
    <div class="modal fade" id="updateCourseModal" tabindex="-1" aria-labelledby="updateCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCourseModalLabel">Update Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="./update/update_course.php">
                        <input type="hidden" name="course_id" id="update_course_id">
                        <div class="mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" name="course_name" id="update_course_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" id="update_description" class="form-control" required>
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Update Course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                paging: false, 
                info: false,   
                lengthChange: false, 
                searching: true 
            });

            $('.update-btn').click(function() {
                $('#update_course_id').val($(this).data('course_id'));
                $('#update_course_name').val($(this).data('course_name'));
                $('#update_description').val($(this).data('description'));
                $('#updateCourseModal').modal('show');
            });
        });
    </script>
</body>
</html>
