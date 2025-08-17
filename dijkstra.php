<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rute Terpendek - Leaflet</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
        .control-buttons {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="control-buttons">
        <button id="useDeviceLocation">Gunakan Lokasi Perangkat</button>
    </div>
    <div id="map"></div>
    <script>
        const endLocation = [5.21629076476602, 97.04883465838894]; // Lokasi tujuan
        let inputDone = false; // Status untuk memastikan input hanya dilakukan sekali

        const map = L.map('map').setView(endLocation, 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker untuk lokasi tujuan
        L.marker(endLocation).addTo(map).bindPopup("Lokasi Akhir").openPopup();

        // Fungsi untuk menambahkan routing control
        function addRoute(startLocation) {
            L.Routing.control({
                waypoints: [
                    L.latLng(startLocation),
                    L.latLng(endLocation)
                ],
                routeWhileDragging: true
            }).addTo(map);
        }

        // Event klik pada peta
        map.on('click', (e) => {
            if (!inputDone) {
                const startLocation = [e.latlng.lat, e.latlng.lng];
                addRoute(startLocation);
                inputDone = true; // Tandai bahwa input telah selesai
            } else {
                alert("Anda sudah memilih lokasi awal!");
            }
        });

        // Fungsi untuk mendapatkan lokasi perangkat
        document.getElementById('useDeviceLocation').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const startLocation = [position.coords.latitude, position.coords.longitude];
                        map.setView(startLocation, 14); // Pindahkan peta ke lokasi perangkat
                        if (!inputDone) {
                            addRoute(startLocation);
                            inputDone = true; // Tandai bahwa input telah selesai
                        } else {
                            alert("Anda sudah memilih lokasi awal!");
                        }
                    },
                    (error) => {
                        alert("Tidak dapat mengakses lokasi perangkat: " + error.message);
                    }
                );
            } else {
                alert("Geolocation tidak didukung oleh browser Anda.");
            }
        });
    </script>
</body>
</html>
