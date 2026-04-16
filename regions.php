<?php include('auth_check.php'); ?>
<?php
include('config.php');
$pageTitle = "Regions";
include('header.php');
include('navbar.php');
?>

<div class="container mt-5">
    <h2 class="page-title">Region List</h2>
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Region ID</th>
                    <th>Region Name</th>
                    <th>Division</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM Region ORDER BY region_id ASC");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                  <td>{$row['region_id']}</td>
                  <td>{$row['region_name']}</td>
                  <td>{$row['division']}</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>