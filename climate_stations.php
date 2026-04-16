<?php
include('config.php');
$pageTitle = "Climate Stations";
include('header.php');
include('navbar.php');
?>

<div class="container mt-5">
    <h2 class="page-title">Climate Stations</h2>
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>Station ID</th>
                    <th>Station Name</th>
                    <th>Region</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT cs.*, r.region_name
                FROM Climate_Station cs
                JOIN Region r ON cs.region_id = r.region_id
                ORDER BY cs.station_id ASC";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                  <td>{$row['station_id']}</td>
                  <td>{$row['station_name']}</td>
                  <td>{$row['region_name']}</td>
                  <td>{$row['latitude']}</td>
                  <td>{$row['longitude']}</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>