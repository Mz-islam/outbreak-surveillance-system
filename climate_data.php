<?php include('auth_check.php'); ?>
<?php
include('config.php');
$pageTitle = "Climate Data";
include('header.php');
include('navbar.php');
?>

<div class="container mt-5">
    <h2 class="page-title">Climate Data</h2>
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Station</th>
                    <th>Region</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Avg Temp</th>
                    <th>Rainfall</th>
                    <th>Humidity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT cd.*, cs.station_name, r.region_name
                FROM Climate_Data cd
                JOIN Climate_Station cs ON cd.station_id = cs.station_id
                JOIN Region r ON cd.region_id = r.region_id
                ORDER BY cd.year DESC, cd.month ASC";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                  <td>{$row['climate_id']}</td>
                  <td>{$row['station_name']}</td>
                  <td>{$row['region_name']}</td>
                  <td>{$row['month']}</td>
                  <td>{$row['year']}</td>
                  <td>{$row['avg_temperature']}</td>
                  <td>{$row['total_rainfall']}</td>
                  <td>{$row['avg_humidity']}</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>