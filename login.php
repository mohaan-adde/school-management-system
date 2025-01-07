<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 link -->

    <style>
        body {
            font-family: sans-serif;
            background-image: url('../images/backimg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .login-container {
            width: 350px;
            background: rgba(242, 242, 242, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            padding: 30px 20px;
            box-sizing: border-box;
        }

        .login-container h2 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .login-container input,
        .login-container select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .login-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .img-container {
            width: 120px;
            height: 120px;
            border-radius: 100%;
            overflow: hidden;
            margin: 0 auto;
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
        }

        .form-footer select {
            width: 48%;
        }

        .form-footer input[type="submit"] {
            width: 48%;
        }

        .link {
            color: #232323;
            text-decoration: none;
            font-size: 12pt;
            display: block;
            text-align: center;
            margin-top: 10px;
        }
    </style>

    <script>
        window.onload = function () {
            document.getElementById("username").disabled = true;
            document.getElementById("password").disabled = true;

            document.getElementById("role").addEventListener("change", function () {
                var category = this.value;
                if (category !== "") {
                    document.getElementById("username").disabled = false;
                    document.getElementById("password").disabled = false;
                } else {
                    document.getElementById("username").disabled = true;
                    document.getElementById("password").disabled = true;
                }
            });
        };
    </script>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <div class="img-container">
            <img src="logo.jpg" alt="Logo">
        </div>

        <form action="sing.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter Username" required disabled>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required disabled>

            <div class="form-footer">
                <input type="submit" value="Login">
                <select id="role" name="role" required>
                    <option value="" selected disabled>Choose Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Staff">Staff</option>
                </select>
            </div>
        </form>
    </div>
</body>
</html>
