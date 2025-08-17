let endLocations = [];
let inputDone = false;
let startMarker;
let routingControl = null;


const map = L.map('map', {
    zoomControl: false, // Hilangkan default zoom control untuk UI yang lebih bersih di mobile
    attributionControl: false
}).setView([5.200730296150841, 97.11048761714383], 13);

L.control.zoom({ position: 'bottomright' }).addTo(map);

// Definisi Layer
const baseMaps = {
        "Default": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }),
        "Satelit": L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles © Esri'
        }),
        "Transport": L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors, OpenTopoMap'
        }),
        "Google Maps": L.tileLayer('https://mt0.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        attribution: '© Google Maps'
        }),
    };

// Tambahkan default layer ke peta
baseMaps["Default"].addTo(map);

// Tambahkan kontrol layer ke peta
L.control.layers(baseMaps).addTo(map);

// Fungsi untuk menambahkan marker
function addMarker(latitude, longtitude, nama, alamat, foto) {
  var popupContent = "<center>" +
                      "<strong>" + nama + "</strong><br>" +
                      alamat + "<br>" +
                      "<img src='" + foto + "' alt='Foto Rumah Sakit' style='width: 100px; height: 100px; margin-top: 10px;'>" +
                      "</center>";

  L.marker([latitude, longtitude])
      .bindPopup(popupContent)
      .addTo(map);
}

// Ambil data dari marker_conn.php
fetch('../../page_user/marker_conn.php')
    .then(response => response.json())
    .then(data => {
        data.forEach(marker => {
            addMarker(marker.latitude, marker.longtitude, marker.nama, marker.alamat, marker.foto);
            // Simpan data rumah sakit ke endLocations
            endLocations.push({
                lat: marker.latitude,
                lon: marker.longtitude,
                name: marker.nama
            });
        });

        console.log("Data rumah sakit berhasil dimuat:", endLocations); // Debugging
    })
    .catch(error => console.error('Error:', error));


function calculateDistance(lat1, lon1, lat2, lon2) {
    return Math.sqrt(Math.pow(lat2 - lat1, 2) + Math.pow(lon2 - lon1, 2));
}

function findNearestLocation(startLocation) {
    if (endLocations.length === 0) {
        alert("Tidak ada rumah sakit dengan spesialis ini.");
        return null;
    }
    return endLocations.reduce((nearest, location) => {
        return calculateDistance(startLocation[0], startLocation[1], location.lat, location.lon) <
            calculateDistance(startLocation[0], startLocation[1], nearest.lat, nearest.lon) ? location : nearest;
    }, endLocations[0]);
}


// Tambahkan elemen CSS langsung dalam JavaScript
let style = document.createElement('style');
style.innerHTML = `
.directions-box {
    position: relative;
    margin-top: 10px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background: #f9f9f9;
    max-height: 200px;
    overflow-y: auto;
    font-size: 12px;
}

.directions-box ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.directions-box ul li {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 5px;
    border-bottom: 1px solid #ddd;
}

.directions-box ul li:last-child {
    border-bottom: none;
}

.directions-box i {
    font-size: 14px;
    color: #007bff; /* Warna ikon biru */
}

.total-info {
    margin-top: 10px;
    font-weight: bold;
}
`;
document.head.appendChild(style);

// Tambahkan elemen div ke dalam peta, bukan ke body
let directionsDiv = L.control({ position: 'topright' });

directionsDiv.onAdd = function (map) {
    let div = L.DomUtil.create('div', 'directions-box');
    div.innerHTML = `<strong>Petunjuk Arah:</strong><ul id="directions-list"></ul>
    <div class="total-info" id="total-info"></div>`;
    return div;
};

// Tambahkan box ke dalam peta
directionsDiv.addTo(map);

// Fungsi untuk menentukan ikon arah berdasarkan teks instruksi
function getDirectionIcon(instruction) {
    instruction = instruction.toLowerCase();

    if (instruction.includes("turn left") || instruction.includes("keep left")) {
        return '<i class="fas fa-arrow-left"></i>'; // Ikon belok kiri
    }
    if (instruction.includes("turn right") || instruction.includes("keep right")) {
        return '<i class="fas fa-arrow-right"></i>'; // Ikon belok kanan
    }
    if (instruction.includes("continue") || instruction.includes("head")) {
        return '<i class="fas fa-arrow-up"></i>'; // Ikon maju lurus
    }
    if (instruction.includes("u-turn")) {
        return '<i class="fas fa-undo"></i>'; // Ikon putar balik
    }
    if (instruction.includes("enter the traffic circle")) {
        return '<i class="fas fa-sync-alt"></i>'; // Ikon bundaran lalu lintas
    }
    if (instruction.includes("exit the traffic circle")) {
        return '<i class="fas fa-sign-out-alt"></i>'; // Ikon keluar bundaran
    }
    if (instruction.includes("arrived at your destination")) {
        return '<i class="fas fa-map-marker-alt"></i>'; // Ikon tujuan akhir
    }
    return '<i class="fas fa-location-arrow"></i>'; // Default ikon navigasi
}

document.addEventListener("DOMContentLoaded", function () {
    let directionsList = document.getElementById("directions-list");
    let items = directionsList.getElementsByTagName("li");

    for (let i = 0; i < items.length; i++) {
        let text = items[i].innerText;
        items[i].innerHTML = `${getDirectionIcon(text)} ${text}`;
    }
});


// Fungsi untuk menambahkan rute dan menampilkan petunjuk arah
function addRoute(startLocation) {
    const nearestEnd = findNearestLocation(startLocation);
    if (!nearestEnd) return;

    // Hapus rute lama jika ada
    if (routingControl) {
        map.removeControl(routingControl);
    }

    routingControl = L.Routing.control({
        waypoints: [
            L.latLng(startLocation),
            L.latLng([nearestEnd.lat, nearestEnd.lon])
        ],
        router: new L.Routing.OSRMv1({
            serviceUrl: 'http://localhost:5000/route/v1'
        }),
        lineOptions: {
            styles: [{
                color: 'blue',
                weight: 6,
                opacity: 0.7
            }]
        },

        routeWhileDragging: true,
        createMarker: function () { return null; },
        summaryTemplate: '', // Mencegah tampilan ringkasan rute
        show: false // Menonaktifkan tampilan petunjuk arah bawaan
    }).addTo(map);

    // Paksa menghapus elemen HTML petunjuk arah bawaan
    document.querySelectorAll('.leaflet-routing-container').forEach(el => el.style.display = 'none');

    routingControl.on('routesfound', function (e) {
        let routes = e.routes[0];
        let distance = (routes.summary.totalDistance / 1000).toFixed(2); // Konversi ke km
        let duration = (routes.summary.totalTime / 60).toFixed(1); // Konversi ke menit

        let instructions = routes.instructions.map(step => `<li>${step.text} (${step.distance} m)</li>`).join('');

        // Tampilkan petunjuk arah, total jarak, dan estimasi waktu
        document.getElementById('directions-list').innerHTML = `
            <strong>Total Jarak:</strong> ${distance} km<br>
            <strong>Estimasi Waktu:</strong> ${duration} menit<br>
            <ul>${instructions}</ul>
        `;

        // Tambahkan ikon ke petunjuk arah
        let directionsList = document.getElementById("directions-list");
        let items = directionsList.getElementsByTagName("li");

        for (let i = 0; i < items.length; i++) {
            let text = items[i].innerText;
            items[i].innerHTML = `${getDirectionIcon(text)} ${text}`;
        }
    });

    alert(`Rute ke rumah sakit terdekat: ${nearestEnd.name}`);
}


// Fungsi untuk menghapus marker lokasi awal jika ada
function removeStartMarker() {
    if (startMarker) {
        map.removeLayer(startMarker);
        startMarker = null;
    }
}

// Event ketika memilih titik di peta
map.on('click', (e) => {
    if (!inputDone) {
        removeStartMarker(); // Hapus marker sebelumnya
        const startLocation = [e.latlng.lat, e.latlng.lng];
        startMarker = L.marker(startLocation).addTo(map).bindPopup("Lokasi Awal").openPopup();
        addRoute(startLocation);
        inputDone = true;
    } else {
        alert("Anda sudah memilih lokasi awal!");
    }
});

document.getElementById('useDeviceLocation').addEventListener('click', () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const startLocation = [position.coords.latitude, position.coords.longitude];
                map.setView(startLocation, 14);
                if (!inputDone) {
                    if (startMarker) {
                        map.removeLayer(startMarker);
                    }
                    startMarker = L.marker(startLocation).addTo(map).bindPopup("Lokasi Awal").openPopup();
                    addRoute(startLocation);
                    inputDone = true;
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
// Ambil daftar gampong dari server dan masukkan ke dropdown
fetch('../page_user/gampong_conn.php')
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById("gampongSelect");
        data.forEach(gampong => {
            let option = document.createElement("option");
            option.value = `${gampong.latitude},${gampong.longtitude}`;
            option.textContent = gampong.nama_gampong;
            select.appendChild(option);
        });
    })
    .catch(error => console.error("Error fetching gampong data:", error));

// Event ketika memilih gampong dari dropdown
document.getElementById("gampongSelect").addEventListener("change", function () {
    if (this.value) {
        removeStartMarker(); // Hapus marker sebelumnya
        const [lat, lon] = this.value.split(",");
        const startLocation = [parseFloat(lat), parseFloat(lon)];

        map.setView(startLocation, 14);
        startMarker = L.marker(startLocation).addTo(map).bindPopup("Lokasi Awal").openPopup();
        addRoute(startLocation);
    }
});

function loadSpesialis() {
    $.getJSON('../page_user/spesialis.php', function(data) {
        data.forEach(function(spesialis) {
            $('#spesialisSelect').append(`<option value="${spesialis.id_spesialis}">${spesialis.nama_spesialis}</option>`);
        });
    });
}

function loadRSBySpesialis(spesialisId) {
    $.getJSON('../page_user/spesialis_conn.php?id_spesialis=' + spesialisId, function(data) {
        map.eachLayer(function(layer) {
            if (layer instanceof L.Marker) {
                map.removeLayer(layer);
            }
        });

        endLocations = []; // Reset daftar rumah sakit

        data.forEach(function(rs) {
            let marker = L.marker([rs.latitude, rs.longtitude]).addTo(map)
                .bindPopup(`<b>${rs.nama}</b><br>${rs.alamat}</b><br>${rs.foto}`);
            
            // Simpan rumah sakit yang memiliki spesialis dalam daftar pencarian
            endLocations.push({
                lat: rs.latitude,
                lon: rs.longtitude,
                name: rs.nama
            });
        });

        console.log("Rumah sakit berdasarkan spesialis:", endLocations); // Debugging
    });
}


$('#spesialisSelect').on('change', function () {
    var selectedSpesialis = $(this).val();
    if (selectedSpesialis) {
        loadRSBySpesialis(selectedSpesialis);
        
        // Jika sudah ada lokasi awal, langsung cari RS terdekat
        if (startMarker) {
            const startLocation = [startMarker.getLatLng().lat, startMarker.getLatLng().lng];
            addRoute(startLocation);
        }
    }
});

  $(document).ready(function() {
    loadSpesialis();
  });