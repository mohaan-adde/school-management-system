<?php
include('../connection/conn.php');


if (isset($_POST['update'])) {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];

    $query = "UPDATE courses SET course_name = '$course_name', description = '$description' WHERE course_id = $course_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: ../courses.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
