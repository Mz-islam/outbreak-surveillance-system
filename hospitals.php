<?php
include('config.php');
$pageTitle = "Hospitals";
include('header.php');
include('navbar.php');
?>

<div class="container mt-5">
    <h2 class="page-title">Hospital List</h2>
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-info">
                <tr>
                    <th>Hospital ID</th>
                    <th>Hospital Name</th>
                    <th>Region</th>
                    <th>Type</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT h.*, r.region_name
                FROM Hospital h
                JOIN Region r ON h.region_id = r.region_id
                ORDER BY h.hospital_id ASC";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                  <td>{$row['hospital_id']}</td>
                  <td>{$row['hospital_name']}</td>
                  <td>{$row['region_name']}</td>
                  <td>{$row['hospital_type']}</td>
                  <td>{$row['contact_number']}</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>