<?php
include('../connection/conn.php');

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    $query = "DELETE FROM courses WHERE course_id = $course_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: ../courses.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
