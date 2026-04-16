<?php include('auth_check.php'); ?>
<?php
include('config.php');
$pageTitle = "Disease Cases";
include('header.php');
include('navbar.php');
?>

<div class="container mt-5">
    <h2 class="page-title">Disease Case Records</h2>
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-danger">
                <tr>
                    <th>Case ID</th>
                    <th>Disease</th>
                    <th>Region</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Total Cases</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT dc.*, d.disease_name, r.region_name
                FROM Disease_Case dc
                JOIN Disease d ON dc.disease_id = d.disease_id
                JOIN Region r ON dc.region_id = r.region_id
                ORDER BY dc.year DESC, dc.month ASC";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                  <td>{$row['case_id']}</td>
                  <td>{$row['disease_name']}</td>
                  <td>{$row['region_name']}</td>
                  <td>{$row['month']}</td>
                  <td>{$row['year']}</td>
                  <td>{$row['total_cases']}</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>