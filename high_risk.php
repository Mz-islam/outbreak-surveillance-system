<?php include('auth_check.php'); ?>
<?php
include('config.php');
$pageTitle = "High Risk Map";
include('header.php');
include('navbar.php');

$riskData = [];

/* -----------------------------
   1) Build risk data for map
----------------------------- */
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

/* -----------------------------
   2) Optional filter by region
----------------------------- */
$selectedRegion = isset($_GET['region']) ? trim($_GET['region']) : '';

$tableSql = "
    SELECT h.*, d.disease_name, r.region_name
    FROM High_Risk_Log h
    JOIN Disease d ON h.disease_id = d.disease_id
    JOIN Region r ON h.region_id = r.region_id
";

if ($selectedRegion !== '') {
    $safeRegion = mysqli_real_escape_string($conn, $selectedRegion);
    $tableSql .= " WHERE r.region_name = '$safeRegion' ";
}

$tableSql .= " ORDER BY h.logged_at DESC, h.total_cases DESC";
$tableResult = mysqli_query($conn, $tableSql);
?>

<div class="container mt-5">
    <h2 class="page-title">Bangladesh High Risk Map</h2>

    <div class="table-container mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-2">Risk Visualization by Region</h4>
            </div>

            <?php if ($selectedRegion !== ''): ?>
                <a href="high_risk.php" class="btn btn-outline-secondary btn-sm">Reset Filter</a>
            <?php endif; ?>
        </div>

        <div class="legend-box mt-3">
            <div class="legend-item"><span class="legend-color color-extreme"></span> EXTREME</div>
            <div class="legend-item"><span class="legend-color color-high"></span> HIGH</div>
            <div class="legend-item"><span class="legend-color color-low"></span> LOW / NO HIGH-RISK DATA</div>
        </div>

        <div id="riskMap"></div>
        <p id="mapStatus" class="mt-3 text-danger fw-semibold"></p>
    </div>

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h4 class="mb-1">High Risk Log Table</h4>
                <?php if ($selectedRegion !== ''): ?>
                    <p class="mb-0 text-muted">
                        Showing filtered records for: <strong><?php echo htmlspecialchars($selectedRegion); ?></strong>
                    </p>
                <?php else: ?>
                    <p class="mb-0 text-muted">Showing all high risk records</p>
                <?php endif; ?>
            </div>
        </div>

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
                if ($tableResult && mysqli_num_rows($tableResult) > 0) {
                    while ($row = mysqli_fetch_assoc($tableResult)) {
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
                } else {
                    echo "<tr><td colspan='8' class='text-center text-muted'>No high risk data found.</td></tr>";
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
    const statusBox = document.getElementById('mapStatus');
    const selectedRegion = <?php echo json_encode($selectedRegion, JSON_UNESCAPED_UNICODE); ?>;

    // GeoJSON region name -> DB region name map
    const geoToDbNameMap = {
        "barisal": "Barishal",
        "barishal": "Barishal",
        "chittagong": "Chattogram",
        "chattogram": "Chattogram",
        "coxs bazar": "Cox's Bazar",
        "cox bazar": "Cox's Bazar",
        "cox's bazar": "Cox's Bazar",
        "jashore": "Jessore",
        "jessore": "Jessore",
        "bogra": "Bogura",
        "bogura": "Bogura",
        "comilla": "Cumilla",
        "cumilla": "Cumilla",
        "dhaka": "Dhaka",
        "gazipur": "Gazipur",
        "narayanganj": "Narayanganj",
        "khulna": "Khulna",
        "rajshahi": "Rajshahi",
        "sylhet": "Sylhet",
        "rangpur": "Rangpur",
        "mymensingh": "Mymensingh",
        "pabna": "Pabna",
        "natore": "Natore",
        "satkhira": "Satkhira",
        "bagerhat": "Bagerhat",
        "noakhali": "Noakhali",
        "cumilla sadar": "Cumilla",
        "rajshahi sadar": "Rajshahi"
    };

    function normalizeName(name) {
        return String(name || "")
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
            .replace(/bogra/g, 'bogura')
            .replace(/comilla/g, 'cumilla')
            .trim();
    }

    function getRegionName(feature) {
        const props = feature.properties || {};

        return (
            props.name ||
            props.NAME ||
            props.Name ||
            props.shapeName ||
            props.admin2Name ||
            props.ADM2_EN ||
            props.district ||
            props.DIST_NAME ||
            props.region_name ||
            props.district_name ||
            props.NAME_2 ||
            props.NAME_1 ||
            "Unknown"
        );
    }

    function getMappedDbRegion(regionName) {
        const normalizedInput = normalizeName(regionName);

        if (geoToDbNameMap[normalizedInput]) {
            return geoToDbNameMap[normalizedInput];
        }

        for (const dbRegion in regionRiskData) {
            if (normalizeName(dbRegion) === normalizedInput) {
                return dbRegion;
            }
        }

        return null;
    }

    function getRiskLevel(regionName) {
        const matchedRegion = getMappedDbRegion(regionName);
        if (matchedRegion && regionRiskData[matchedRegion]) {
            return regionRiskData[matchedRegion].risk_level;
        }
        return "LOW";
    }

    function getCaseCount(regionName) {
        const matchedRegion = getMappedDbRegion(regionName);
        if (matchedRegion && regionRiskData[matchedRegion]) {
            return regionRiskData[matchedRegion].total_cases;
        }
        return 0;
    }

    function getColorByRisk(risk) {
        if (risk === "EXTREME") return "#dc3545";
        if (risk === "HIGH") return "#fd7e14";
        return "#4dabf7";
    }

    function styleFeature(feature) {
        const regionName = getRegionName(feature);
        const risk = getRiskLevel(regionName);
        const mappedDbRegion = getMappedDbRegion(regionName);

        let baseStyle = {
            fillColor: getColorByRisk(risk),
            weight: 1.5,
            opacity: 1,
            color: "#333",
            dashArray: "3",
            fillOpacity: 0.65
        };

        // যদি selected region থাকে, selected region highlight করো
        if (selectedRegion && mappedDbRegion === selectedRegion) {
            baseStyle.weight = 3;
            baseStyle.color = "#000";
            baseStyle.fillOpacity = 0.9;
        }

        return baseStyle;
    }

    let geojsonLayer;
    const extremeLayers = [];

    function onEachFeature(feature, layer) {
        const regionName = getRegionName(feature);
        const mappedDbRegion = getMappedDbRegion(regionName);
        const risk = getRiskLevel(regionName);
        const cases = getCaseCount(regionName);

        console.log("GeoJSON region:", regionName, "| DB match:", mappedDbRegion, "| risk:", risk, "| cases:", cases);

        // 1) Hover tooltip
        layer.bindTooltip(
            `${mappedDbRegion ? mappedDbRegion : regionName} - ${risk}`,
            { sticky: true }
        );

        // 2) Click popup
        layer.bindPopup(`
            <div style="min-width:220px;">
                <h6><strong>${mappedDbRegion ? mappedDbRegion : regionName}</strong></h6>
                <p style="margin:0;"><strong>Risk Level:</strong> ${risk}</p>
                <p style="margin:0;"><strong>Total Cases:</strong> ${cases}</p>
            </div>
        `);

        // 3) Mouse hover style
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

        // 4) Click করলে zoom + table filter
        layer.on('click', function () {
            map.fitBounds(layer.getBounds(), { padding: [20, 20] });

            if (mappedDbRegion) {
                window.location.href = "high_risk.php?region=" + encodeURIComponent(mappedDbRegion);
            }
        });

        // 5) EXTREME হলে pulse animation list-এ রাখো
        if (risk === "EXTREME") {
            extremeLayers.push(layer);
        }
    }

    fetch('assets/bangladesh.geojson')
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error: ' + response.status + ' (GeoJSON file not found)');
            }
            return response.json();
        })
        .then(data => {
            statusBox.textContent = '';

            geojsonLayer = L.geoJSON(data, {
                style: styleFeature,
                onEachFeature: onEachFeature
            }).addTo(map);

            if (selectedRegion) {
                // যদি filtered region থাকে, ওই region-এ zoom করো
                geojsonLayer.eachLayer(function (layer) {
                    const regionName = getRegionName(layer.feature);
                    const mappedDbRegion = getMappedDbRegion(regionName);

                    if (mappedDbRegion === selectedRegion) {
                        map.fitBounds(layer.getBounds(), { padding: [20, 20] });
                    }
                });
            } else {
                map.fitBounds(geojsonLayer.getBounds());
            }

            console.log('GeoJSON loaded successfully', data);

            // 6) EXTREME region subtle pulse effect
            let pulse = false;
            setInterval(() => {
                pulse = !pulse;
                extremeLayers.forEach(layer => {
                    layer.setStyle({
                        fillOpacity: pulse ? 0.95 : 0.55
                    });
                });
            }, 900);
        })
        .catch(error => {
            console.error('GeoJSON load error:', error);
            statusBox.textContent = 'Map is not loading,check map file location path. Error: ' + error.message;
        });
</script>

<?php include('footer.php'); ?>