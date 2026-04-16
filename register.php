<?php
include('config.php');

$message = "";

$admin_secret_code = "CLIMATE2026ADMIN";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $user_id = trim($_POST['user_id']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $secret_code = trim($_POST['secret_code']);

    if ($email == "" || $user_id == "" || $password == "" || $confirm_password == "" || $secret_code == "") {
        $message = "<div class='alert alert-warning'>All fields are required.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-warning'>Invalid email format.</div>";
    } elseif ($password !== $confirm_password) {
        $message = "<div class='alert alert-danger'>Passwords do not match.</div>";
    } elseif ($secret_code !== $admin_secret_code) {
        $message = "<div class='alert alert-danger'>Invalid administrator access code.</div>";
    } else {
        $check_sql = "SELECT * FROM Admin_User WHERE email='$email' OR user_id='$user_id'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "<div class='alert alert-danger'>Email or User ID already exists.</div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_sql = "INSERT INTO Admin_User (email, user_id, password, role)
                           VALUES ('$email', '$user_id', '$hashed_password', 'Administrator')";

            if (mysqli_query($conn, $insert_sql)) {
                $message = "<div class='alert alert-success'>Administrator account created successfully. Please login now.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="login-bg">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card auth-card p-4">
                    <h2 class="text-center mb-3">Administrator Registration</h2>
                    <p class="text-center text-muted">Restricted registration for authorized research personnel only</p>

                    <?php echo $message; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Email ID</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>User ID</label>
                            <input type="text" name="user_id" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Administrator Access Code</label>
                            <input type="text" name="secret_code" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Create Administrator Account</button>
                    </form>

                    <p class="text-center mt-3">
                        Already have an account? <a href="login.php">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>