<?php include('auth_check.php'); ?>
<?php
include('config.php');
$pageTitle = "Analysis Dashboard";
include('header.php');
include('navbar.php');

$regions = [];
$totalCases = [];
$q1 = "SELECT r.region_name, SUM(dc.total_cases) AS total_cases
       FROM Disease_Case dc
       JOIN Region r ON dc.region_id = r.region_id
       GROUP BY r.region_name
       ORDER BY total_cases DESC";
$r1 = mysqli_query($conn, $q1);
while ($row = mysqli_fetch_assoc($r1)) {
    $regions[] = $row['region_name'];
    $totalCases[] = (int) $row['total_cases'];
}

$diseaseNames = [];
$diseaseTotals = [];
$q2 = "SELECT d.disease_name, SUM(dc.total_cases) AS total_cases
       FROM Disease_Case dc
       JOIN Disease d ON dc.disease_id = d.disease_id
       GROUP BY d.disease_name
       ORDER BY total_cases DESC";
$r2 = mysqli_query($conn, $q2);
while ($row = mysqli_fetch_assoc($r2)) {
    $diseaseNames[] = $row['disease_name'];
    $diseaseTotals[] = (int) $row['total_cases'];
}

$months = [];
$dengueCases = [];
$q3 = "SELECT month, SUM(total_cases) AS dengue_cases
       FROM Disease_Case
       WHERE disease_id = 1
       GROUP BY month
       ORDER BY month";
$r3 = mysqli_query($conn, $q3);
while ($row = mysqli_fetch_assoc($r3)) {
    $months[] = "Month " . $row['month'];
    $dengueCases[] = (int) $row['dengue_cases'];
}
?>

<div class="container mt-5">
    <h2 class="page-title">Analytical Dashboard</h2>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="table-container">
                <h5>Region-wise Total Disease Cases</h5>
                <canvas id="regionCasesChart"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="table-container">
                <h5>Disease-wise Total Cases</h5>
                <canvas id="diseasePieChart"></canvas>
            </div>
        </div>

        <div class="col-md-12">
            <div class="table-container">
                <h5>Monthly Dengue Trend</h5>
                <canvas id="dengueLineChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('regionCasesChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($regions); ?>,
            datasets: [{
                label: 'Total Cases',
                data: <?php echo json_encode($totalCases); ?>,
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('diseasePieChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($diseaseNames); ?>,
            datasets: [{
                data: <?php echo json_encode($diseaseTotals); ?>
            }]
        },
        options: { responsive: true }
    });

    new Chart(document.getElementById('dengueLineChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Dengue Cases',
                data: <?php echo json_encode($dengueCases); ?>,
                fill: false,
                tension: 0.3
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>

<?php include('footer.php'); ?>