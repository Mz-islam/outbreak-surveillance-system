<?php
session_start();
include('config.php');

echo "Logout page reached.<br>";

if (isset($_SESSION['history_id'])) {
    $history_id = $_SESSION['history_id'];
    echo "History ID found: " . $history_id . "<br>";

    $sql = "UPDATE admin_login_history
            SET logout_time = NOW(),
                session_status = 'LOGGED_OUT'
            WHERE history_id = '$history_id'";

    if (mysqli_query($conn, $sql)) {
        echo "Logout time updated successfully.<br>";
    } else {
        echo "SQL Error: " . mysqli_error($conn);
    }
} else {
    echo "No history_id found in session.<br>";
}

session_unset();
session_destroy();

// header("Location: login.php");
// exit();
?>