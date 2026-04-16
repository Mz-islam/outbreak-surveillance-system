<?php include('auth_check.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Administration Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="dashboard-bg">

    <nav class="navbar navbar-dark bg-dark px-4">
        <span class="navbar-brand">Research Administration Panel</span>
        <div class="text-white">
            <?php echo $_SESSION['user_id']; ?> (
            <?php echo $_SESSION['role']; ?>)
            <a href="logout.php" class="btn btn-danger btn-sm ms-3">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card admin-card p-4">
            <h2>Welcome to the Administrative Dashboard</h2>
            <p class="text-muted">
                This system is restricted to authorized administrative and research personnel only.
            </p>

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <a href="index.php" class="btn btn-primary w-100 p-3">Main Dashboard</a>
                </div>
                <div class="col-md-4">
                    <a href="regions.php" class="btn btn-success w-100 p-3">Regions</a>
                </div>
                <div class="col-md-4">
                    <a href="climate_data.php" class="btn btn-info w-100 p-3">Climate Data</a>
                </div>
                <div class="col-md-4">
                    <a href="disease_cases.php" class="btn btn-warning w-100 p-3">Disease Cases</a>
                </div>
                <div class="col-md-4">
                    <a href="high_risk.php" class="btn btn-danger w-100 p-3">High Risk Map</a>
                </div>
                <div class="col-md-4">
                    <a href="analysis.php" class="btn btn-dark w-100 p-3">Analysis</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>