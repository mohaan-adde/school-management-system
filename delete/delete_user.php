<?php
include('../connection/conn.php');

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $query = "DELETE FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: ../users.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
