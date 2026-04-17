<?php
session_start();
include('config.php');

$message = "";

// Show timeout message
if (isset($_GET['timeout'])) {
    $message = "<div class='alert alert-warning'>Session expired due to inactivity. Please login again.</div>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = trim($_POST['user_id']);
    $password = trim($_POST['password']);

    if ($user_id == "" || $password == "") {
        $message = "<div class='alert alert-warning'>Please enter User ID and Password.</div>";
    } else {
        $sql = "SELECT * FROM Admin_User WHERE user_id='$user_id' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['admin_id'];
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['last_activity'] = time();

                $admin_id = $row['admin_id'];
                $login_user_id = $row['user_id'];

                // Insert login history
                $history_sql = "INSERT INTO admin_login_history (admin_id, user_id, login_time, session_status)
                                VALUES ('$admin_id', '$login_user_id', NOW(), 'ACTIVE')";
                mysqli_query($conn, $history_sql);

                $_SESSION['history_id'] = mysqli_insert_id($conn);

                header("Location: admin_dashboard.php");
                exit();
            } else {
                $message = "<div class='alert alert-danger'>Invalid password.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Administrator not found.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="login-bg">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card auth-card p-4">
                    <h2 class="text-center mb-3">Administrative Login</h2>
                    <p class="text-center text-muted">Authorized access only</p>

                    <?php echo $message; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>User ID</label>
                            <input type="text" name="user_id" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Login</button>
                    </form>

                    <p class="text-center mt-3">
                        New administrator? <a href="register.php">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>