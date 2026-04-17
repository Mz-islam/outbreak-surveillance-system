<?php include('auth_check.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Administration Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="ui-body">

    <!-- Top Navigation -->
    <header class="top-nav">
        <div class="nav-left">
            <div class="brand-mark"></div>
            <div class="brand-text">
                <h1>Research Administration</h1>
                <span>Climate–Disease Intelligence System</span>
            </div>
        </div>

        <div class="nav-right">
            <div class="user-chip">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['user_id'], 0, 1)); ?>
                </div>
                <div class="user-details">
                    <strong><?php echo $_SESSION['user_id']; ?></strong>
                    <span><?php echo $_SESSION['role']; ?></span>
                </div>
            </div>

            <a href="logout.php" class="logout-btn">
                <i data-lucide="log-out"></i>
                <span>Logout</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-wrap">

        <section class="hero-panel">
            <div class="hero-copy">
                <p class="hero-label">Administrative Workspace</p>
                <h2>Welcome back to the surveillance control center</h2>
                <p>
                    Monitor climate indicators, disease case records, outbreak severity,
                    and analytical insights from one unified administrative platform.
                </p>
            </div>

            <div class="hero-side-card">
                <div class="mini-stat">
                    <span class="mini-stat-label">System Access</span>
                    <strong>Authorized Only</strong>
                </div>
                <div class="mini-stat">
                    <span class="mini-stat-label">Session Role</span>
                    <strong><?php echo $_SESSION['role']; ?></strong>
                </div>
            </div>
        </section>

        <section class="dashboard-grid">

            <a href="index.php" class="dash-card card-large card-main">
                <div class="card-icon">
                    <i data-lucide="layout-dashboard"></i>
                </div>
                <div class="card-content">
                    <span class="card-kicker">Core Workspace</span>
                    <h3>Main Dashboard</h3>
                    <p>Open the central control panel with overall system summaries and quick monitoring access.</p>
                </div>
                <div class="card-line"></div>
            </a>

            <a href="climate_data.php" class="dash-card card-small card-climate">
                <div class="card-icon">
                    <i data-lucide="cloud-rain-wind"></i>
                </div>
                <div class="card-content">
                    <span class="card-kicker">Environmental Module</span>
                    <h3>Climate Data</h3>
                    <p>Temperature, rainfall, humidity, and station-linked records.</p>
                </div>
            </a>

            <a href="disease_cases.php" class="dash-card card-small card-disease">
                <div class="card-icon">
                    <i data-lucide="activity"></i>
                </div>
                <div class="card-content">
                    <span class="card-kicker">Outbreak Tracking</span>
                    <h3>Disease Cases</h3>
                    <p>Region-wise case reports and monthly outbreak patterns.</p>
                </div>
            </a>

            <a href="regions.php" class="dash-card card-medium card-region">
                <div class="card-icon">
                    <i data-lucide="map-pinned"></i>
                </div>
                <div class="card-content">
                    <span class="card-kicker">Location Management</span>
                    <h3>Regions</h3>
                    <p>Manage administrative regions and surveillance geography.</p>
                </div>
            </a>

            <a href="analysis.php" class="dash-card card-medium card-analysis">
                <div class="card-icon">
                    <i data-lucide="bar-chart-3"></i>
                </div>
                <div class="card-content">
                    <span class="card-kicker">Insight Engine</span>
                    <h3>Analysis</h3>
                    <p>Explore trends, comparisons, and climate-disease relationships visually.</p>
                </div>
            </a>

            <a href="high_risk.php" class="dash-card card-wide card-risk">
                <div class="card-icon">
                    <i data-lucide="shield-alert"></i>
                </div>
                <div class="card-content">
                    <span class="card-kicker">Critical Monitoring</span>
                    <h3>High Risk Map</h3>
                    <p>Identify hotspots, outbreak severity, and geographic risk concentration in real time.</p>
                </div>
                <div class="risk-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>

        </section>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>