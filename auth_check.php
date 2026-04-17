<?php
session_start();
include('config.php');

$timeout_duration = 1800; // 30 minutes = 1800 seconds

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check inactivity timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {

    // Update timeout logout info in history table
    if (isset($_SESSION['history_id'])) {
        $history_id = $_SESSION['history_id'];

        $sql = "UPDATE admin_login_history
                SET logout_time = NOW(),
                    session_status = 'TIMEOUT'
                WHERE history_id = '$history_id'";
        mysqli_query($conn, $sql);
    }

    session_unset();
    session_destroy();

    header("Location: login.php?timeout=1");
    exit();
}

// Update last activity timestamp
$_SESSION['last_activity'] = time();
?>