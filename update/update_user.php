<?php
include('../connection/conn.php');

if (isset($_POST['update'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password']; 

    if (!empty($password)) {
        $query = "UPDATE users SET username = '$username', email = '$email', role = '$role', password = '$password' WHERE user_id = $user_id";
    } else {
        $query = "UPDATE users SET username = '$username', email = '$email', role = '$role' WHERE user_id = $user_id";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: ../users.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
