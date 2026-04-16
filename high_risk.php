<?php include('auth_check.php'); ?>
<?php
include('config.php');
$pageTitle = "High Risk Map";
include('header.php');
include('navbar.php');

$riskData = [];

$riskQuery = "
    SELECT 
        r.region_name,
        MAX(
            CASE
                WHEN h.risk_level = 'EXTREME' THEN 3
                WHEN h.risk_level = 'HIGH' THEN 2
                WHEN h.risk_level = 'LOW' THEN 1
                ELSE 0
            END
        ) AS risk_rank,
        MAX(h.total_cases) AS max_cases
    FROM Region r
    LEFT JOIN High_Risk_Log h ON r.region_id = h.region_id
    GROUP BY r.region_id, r.region_name
";

$riskResult = mysqli_query($conn, $riskQuery);

while ($row = mysqli_fetch_assoc($riskResult)) {
    $riskLevel = "LOW";

    if ((int) $row['risk_rank'] === 3) {
        $riskLevel = "EXTREME";
    } elseif ((int) $row['risk_rank'] === 2) {
        $riskLevel = "HIGH";
    } elseif ((int) $row['risk_rank'] === 1) {
        $riskLevel = "LOW";
    }

    $riskData[$row['region_name']] = [
        "risk_level" => $riskLevel,
        "total_cases" => $row['max_cases'] ? (int) $row['max_cases'] : 0
    ];
}
?>

<div class="container mt-5">
    <h2 class="page-title">Bangladesh High Risk Map</h2>

    <div class="table-container mb-4">
        <h4 class="mb-3">Risk Visualization by Region</h4>

        <div class="legend-box">
            <div class="legend-item"><span class="legend-color color-extreme"></span> EXTREME</div>
            <div class="legend-item"><span class="legend-color color-high"></span> HIGH</div>
            <div class="legend-item"><span class="legend-color color-low"></span> LOW / NO HIGH-RISK DATA</div>
        </div>

        <div id="riskMap"></div>
        <p id="mapStatus" class="mt-3 text-danger fw-semibold"></p>
    </div>

    <div class="table-container">
        <h4 class="mb-3">High Risk Log Table</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-danger">
                <tr>
                    <th>Risk ID</th>
                    <th>Disease</th>
                    <th>Region</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Total Cases</th>
                    <th>Risk Level</th>
                    <th>Logged At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT h.*, d.disease_name, r.region_name
                FROM High_Risk_Log h
                JOIN Disease d ON h.disease_id = d.disease_id
                JOIN Region r ON h.region_id = r.region_id
                ORDER BY h.logged_at DESC, h.total_cases DESC";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                  <td>{$row['risk_id']}</td>
                  <td>{$row['disease_name']}</td>
                  <td>{$row['region_name']}</td>
                  <td>{$row['month']}</td>
                  <td>{$row['year']}</td>
                  <td>{$row['total_cases']}</td>
                  <td>{$row['risk_level']}</td>
                  <td>{$row['logged_at']}</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const map = L.map('riskMap').setView([23.6850, 90.3563], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const regionRiskData = <?php echo json_encode($riskData, JSON_UNESCAPED_UNICODE); ?>;
    const geoToDbNameMap = {
        "chittagong": "Chattogram",
        "chattogram": "Chattogram",
        "coxs bazar": "Cox's Bazar",
        "cox bazar": "Cox's Bazar",
        "cox's bazar": "Cox's Bazar",
        "jashore": "Jessore",
        "jessore": "Jessore",
        "dhaka": "Dhaka",
        "gazipur": "Gazipur",
        "narayanganj": "Narayanganj",
        "khulna": "Khulna",
        "rajshahi": "Rajshahi",
        "sylhet": "Sylhet",
        "rangpur": "Rangpur"
    };
    const statusBox = document.getElementById('mapStatus');

    function getRegionName(feature) {
        return (
            feature.properties.name ||
            feature.properties.NAME ||
            feature.properties.Name ||
            feature.properties.shapeName ||
            feature.properties.admin2Name ||
            feature.properties.ADM2_EN ||
            feature.properties.district ||
            feature.properties.DIST_NAME ||
            feature.properties.region_name ||
            feature.properties.district_name ||
            feature.properties.NAME_2 ||
            feature.properties.NAME_1 ||
            "Unknown"
        );
        console.log(feature.properties);
    }

    function normalizeName(name) {
        return String(name)
            .trim()
            .toLowerCase()
            .replace(/district/g, '')
            .replace(/division/g, '')
            .replace(/zila/g, '')
            .replace(/city corporation/g, '')
            .replace(/\./g, '')
            .replace(/,/g, '')
            .replace(/\s+/g, ' ')
            .replace(/chittagong/g, 'chattogram')
            .replace(/coxs bazar/g, "cox's bazar")
            .replace(/cox bazar/g, "cox's bazar")
            .replace(/jashore/g, 'jessore')
            .replace(/narayanganj district/g, 'narayanganj')
            .replace(/dhaka district/g, 'dhaka')
            .replace(/rajshahi district/g, 'rajshahi')
            .replace(/sylhet district/g, 'sylhet')
            .replace(/rangpur district/g, 'rangpur')
            .trim();
    }

    function getRiskLevel(regionName) {
        const normalizedInput = normalizeName(regionName);
        const mappedDbName = geoToDbNameMap[normalizedInput];

        if (mappedDbName && regionRiskData[mappedDbName]) {
            return regionRiskData[mappedDbName].risk_level;
        }

        for (const dbRegion in regionRiskData) {
            if (normalizeName(dbRegion) === normalizedInput) {
                return regionRiskData[dbRegion].risk_level;
            }
        }

        return "LOW";
    }

    function getCaseCount(regionName) {
        const normalizedInput = normalizeName(regionName);
        const mappedDbName = geoToDbNameMap[normalizedInput];

        if (mappedDbName && regionRiskData[mappedDbName]) {
            return regionRiskData[mappedDbName].total_cases;
        }

        for (const dbRegion in regionRiskData) {
            if (normalizeName(dbRegion) === normalizedInput) {
                return regionRiskData[dbRegion].total_cases;
            }
        }

        return 0;
    }
    function getColorByRisk(risk) {
        if (risk === "EXTREME") return "red";
        if (risk === "HIGH") return "orange";
        return "blue";
    }

    function styleFeature(feature) {
        const regionName = getRegionName(feature);
        const risk = getRiskLevel(regionName);

        return {
            fillColor: getColorByRisk(risk),
            weight: 1.5,
            opacity: 1,
            color: "#333",
            dashArray: "3",
            fillOpacity: 0.65
        };
    }

    let geojsonLayer;

    function onEachFeature(feature, layer) {
        const regionName = getRegionName(feature);
        const risk = getRiskLevel(regionName);
        const cases = getCaseCount(regionName);
        console.log("GeoJSON region:", regionName, "=> risk:", risk);

        layer.bindPopup(`
        <div style="min-width:200px;">
            <h6><strong>${regionName}</strong></h6>
            <p style="margin:0;"><strong>Risk Level:</strong> ${risk}</p>
            <p style="margin:0;"><strong>Total Cases:</strong> ${cases}</p>
        </div>
    `);

        layer.on({
            mouseover: function (e) {
                const l = e.target;
                l.setStyle({
                    weight: 3,
                    color: '#000',
                    fillOpacity: 0.85
                });
                l.bringToFront();
            },
            mouseout: function (e) {
                geojsonLayer.resetStyle(e.target);
            }
        });
    }

    /*
      IMPORTANT:
      assets folder-er exact geojson filename ekhane boshao
    */
    fetch('assets/bangladesh.geojson')
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            statusBox.textContent = '';
            geojsonLayer = L.geoJSON(data, {
                style: styleFeature,
                onEachFeature: onEachFeature
            }).addTo(map);

            map.fitBounds(geojsonLayer.getBounds());
            console.log('GeoJSON loaded successfully', data);
        })
        .catch(error => {
            console.error('GeoJSON load error:', error);
            statusBox.textContent = 'Map load hocche na. File path/name check koro: ' + error.message;
        });
</script>

<?php include('footer.php'); ?>