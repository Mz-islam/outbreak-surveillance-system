<?php include('auth_check.php'); ?>
<?php
include('config.php');
$pageTitle = "Home Dashboard";
include('header.php');
include('navbar.php');

$regionCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM Region"))['total'] ?? 0;
$stationCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM Climate_Station"))['total'] ?? 0;
$diseaseCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM Disease"))['total'] ?? 0;
$caseCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_cases),0) AS total FROM Disease_Case"))['total'] ?? 0;
$riskCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM High_Risk_Log"))['total'] ?? 0;
$hospitalCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM Hospital"))['total'] ?? 0;
$alertCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM Alert_Notification"))['total'] ?? 0;
?>

<div class="container mt-5">
    <h2 class="page-title text-center">Outbreak Intelligence System</h2>
    <p class="text-center text-muted">A database-based surveillance system designed to integrate climate
        and disease data into a single platform</p>

    <div class="row g-4 mt-3">
        <div class="col-md-3">
            <div class="card card-box p-4 bg-primary text-white text-center">
                <h3>
                    <?php echo $regionCount; ?>
                </h3>
                <p class="mb-0">Regions</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-box p-4 bg-success text-white text-center">
                <h3>
                    <?php echo $stationCount; ?>
                </h3>
                <p class="mb-0">Climate Stations</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-box p-4 bg-warning text-dark text-center">
                <h3>
                    <?php echo $diseaseCount; ?>
                </h3>
                <p class="mb-0">Diseases</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-box p-4 bg-danger text-white text-center">
                <h3>
                    <?php echo $riskCount; ?>
                </h3>
                <p class="mb-0">High Risk Logs</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-box p-4 bg-info text-dark text-center">
                <h3>
                    <?php echo $caseCount; ?>
                </h3>
                <p class="mb-0">Total Cases</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-box p-4 bg-secondary text-white text-center">
                <h3>
                    <?php echo $hospitalCount; ?>
                </h3>
                <p class="mb-0">Hospitals</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-box p-4 bg-light text-dark text-center">
                <h3>
                    <?php echo $alertCount; ?>
                </h3>
                <p class="mb-0">Alerts</p>
            </div>
        </div>
    </div>

    <div class="row mt-5 g-4">
        <div class="col-md-6">
            <div class="table-container">
                <h4>System Modules</h4>
                <ul>
                    <li>Climate monitoring and station management</li>
                    <li>Disease and case surveillance</li>
                    <li>High-risk outbreak detection</li>
                    <li>Hospital and healthcare resource tracking</li>
                    <li>Alert notification and preventive action monitoring</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-container">
                <h4>Purpose</h4>
                <p>
                    This system integrates climate data, disease cases, healthcare resources,
                    alerts and response measures into one structured relational database for
                    investigation and decision support.
                </p>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>