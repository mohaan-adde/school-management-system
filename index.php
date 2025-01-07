
<?php
include('./connection/conn.php'); 

$studentQuery = "SELECT COUNT(*) AS total_students FROM students";
$studentResult = mysqli_query($conn, $studentQuery);
$studentData = mysqli_fetch_assoc($studentResult);
$totalStudents = $studentData['total_students'];

// $enrollmentQuery = "SELECT COUNT(*) AS total_enrollments FROM enrollment";
// $enrollmentResult = mysqli_query($conn, $enrollmentQuery);
// $enrollmentData = mysqli_fetch_assoc($enrollmentResult);
// $totalEnrollments = $enrollmentData['total_enrollments'];

$teacherQuery = "SELECT COUNT(*) AS total_teacher FROM teacher";
$teacherResult = mysqli_query($conn, $teacherQuery);
$teacherData = mysqli_fetch_assoc($teacherResult);
$totalteacher = $teacherData['total_teacher'];

$courseQuery = "SELECT COUNT(*) AS total_courses FROM courses";
$courseResult = mysqli_query($conn, $courseQuery);
$courseData = mysqli_fetch_assoc($courseResult);
$totalCourses = $courseData['total_courses'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
   

</head>
<body>
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
    <?php
    session_start(); 
    if (isset($_SESSION['username'])) {
        echo "<p>Welcome, " . $_SESSION['username'] . "</p>";
    } else {
        echo "<p>Welcome, Guest</p>";
    }
    ?>
    <br>
    <br>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>


    </div>

    <div class="main-content">
        <h1 class="main-title">Dashboard</h1>
        <div class="stats-cards">
            <div class="card">
                <div class="card-icon">👥</div>
                <h2>Total Students</h2>
                <h3><?php echo number_format($totalStudents); ?></h3>
                </div>
            <br>
            <div class="card">
                <div class="card-icon">👥</div>
                <h2>Total teachers</h2>
                <h3><?php echo number_format($totalteacher); ?></h3>
            </div>
            <div class="card">
                <div class="card-icon">🎓</div>
                <h2>Total Courses</h2>
                <h3><?php echo number_format(num: $totalCourses); ?></h3>
                </div>
            <div class="card">
                <div class="card-icon">📖</div>
                <h2>Total Enrollments</h2>
                <h3><?php echo number_format(num: $totalCourses); ?></h3>
                </div>
        </div>
    </div>
</body>
</html>
