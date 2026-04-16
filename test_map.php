<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GeoJSON Test Map</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            height: 100vh;
            width: 100%;
        }
    </style>
</head>

<body>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([23.6850, 90.3563], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        fetch('assets/bangladesh.geojson')
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(data => {
                console.log('GeoJSON loaded:', data);

                const layer = L.geoJSON(data, {
                    style: {
                        color: 'black',
                        weight: 1,
                        fillColor: 'orange',
                        fillOpacity: 0.5
                    },
                    onEachFeature: function (feature, layer) {
                        layer.bindPopup(JSON.stringify(feature.properties));
                    }
                }).addTo(map);

                map.fitBounds(layer.getBounds());
            })
            .catch(err => {
                console.error(err);
                alert('GeoJSON load error: ' + err.message);
            });
    </script>

</body>

</html>