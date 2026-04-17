<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">

        <!-- LEFT: Brand -->
        <a class="navbar-brand fw-bold" href="index.php">
            Climate-Disease Surveillance Dashboard
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu + Logout -->
        <div class="collapse navbar-collapse" id="navMenu">

            <!-- LEFT MENU -->
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="regions.php">Regions</a></li>
                <li class="nav-item"><a class="nav-link" href="climate_stations.php">Stations</a></li>
                <li class="nav-item"><a class="nav-link" href="climate_data.php">Climate</a></li>
                <li class="nav-item"><a class="nav-link" href="diseases.php">Diseases</a></li>
                <li class="nav-item"><a class="nav-link" href="disease_cases.php">Cases</a></li>
                <li class="nav-item"><a class="nav-link" href="high_risk.php">High Risk</a></li>
                <li class="nav-item"><a class="nav-link" href="hospitals.php">Hospitals</a></li>
                <li class="nav-item"><a class="nav-link" href="alerts.php">Alerts</a></li>
                <li class="nav-item"><a class="nav-link" href="preventive_actions.php">Actions</a></li>
                <li class="nav-item"><a class="nav-link" href="analysis.php">Analysis</a></li>
            </ul>

            <!-- RIGHT SIDE -->
            <div class="ms-auto">
                <a href="logout.php" class="logout-btn">
                    <i data-lucide="log-out"></i>
                    <span>Logout</span>
                </a>
            </div>

        </div>
    </div>
</nav>