<?php
include('config.php');
$pageTitle = "Alerts";
include('header.php');
include('navbar.php');
?>

<div class="container mt-5">
    <h2 class="page-title">Alert Notifications</h2>
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-warning">
                <tr>
                    <th>Alert ID</th>
                    <th>Risk ID</th>
                    <th>Message</th>
                    <th>Sent To</th>
                    <th>Sent Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM Alert_Notification ORDER BY alert_id ASC");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                  <td>{$row['alert_id']}</td>
                  <td>{$row['risk_id']}</td>
                  <td>{$row['message']}</td>
                  <td>{$row['sent_to']}</td>
                  <td>{$row['sent_date']}</td>
                  <td>{$row['alert_status']}</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>