<?php
include('config.php');
$pageTitle = "Preventive Actions";
include('header.php');
include('navbar.php');
?>

<div class="container mt-5">
    <h2 class="page-title">Preventive Actions</h2>
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>Action ID</th>
                    <th>Disease</th>
                    <th>Region</th>
                    <th>Action Name</th>
                    <th>Action Date</th>
                    <th>Authority</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT pa.*, d.disease_name, r.region_name
                FROM Preventive_Action pa
                JOIN Disease d ON pa.disease_id = d.disease_id
                JOIN Region r ON pa.region_id = r.region_id
                ORDER BY pa.action_id ASC";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                  <td>{$row['action_id']}</td>
                  <td>{$row['disease_name']}</td>
                  <td>{$row['region_name']}</td>
                  <td>{$row['action_name']}</td>
                  <td>{$row['action_date']}</td>
                  <td>{$row['authority_name']}</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>