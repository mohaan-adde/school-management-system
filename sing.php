<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
</head>
<body>
<?php
session_start();
include "./connection/conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($username) || empty($password)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Username and password cannot be empty!',
                    confirmButtonText: 'Try Again'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'sing.php';
                    }
                });
              </script>";
        exit();
    }

    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    if ($role == 'Admin' || $role == 'Staff') {
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='$role'";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Invalid role selected!',
                    confirmButtonText: 'Try Again'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'sing.php';
                    }
                });
              </script>";
        exit();
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); 

        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $role;

        switch ($role) {
            case 'Admin':
                $_SESSION['fullname'] = $row['fullname']; 
                echo "<script>window.location.href = 'index.php';</script>";
                break;
            case 'Staff':
                echo "<script>window.location.href = 'index.php';</script>";
                break;
            default:
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Invalid role selected!',
                            confirmButtonText: 'Try Again'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'sing.php';
                            }
                        });
                      </script>";
                break;
        }
        exit();
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Username or password is incorrect!',
                    confirmButtonText: 'Try Again'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'sing.php';
                    }
                });
              </script>";
        exit();
    }
}
?>
</body>
</html>
