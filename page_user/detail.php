<?php include "header.php"; ?>
<?php
$id = $_GET['id'];
include_once "ambildata_id.php";
$obj = json_decode($data);

// Inisialisasi variabel
$id = $nama = $alamat = $deskripsi = $akreditasi = $tipe_kelas = $lat = $long = $foto = "";
$spesialis_list = array();

// Ambil data rumah sakit
foreach ($obj->results as $item) {
    $id = $item->id;
    $nama = $item->nama;
    $alamat = $item->alamat;
    $deskripsi = $item->deskripsi;
    $akreditasi = $item->akreditasi;
    $tipe_kelas = $item->tipe_kelas;
    $lat = $item->latitude;
    $long = $item->longtitude;
    $foto = $item->foto;
}

// Ambil daftar spesialis rumah sakit
$spesialis_list = $obj->spesialis;
$title = "Detail dan Lokasi : " . $nama;
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script src="https://unpkg.com/leaflet-providers"></script>

<style>
  #map {
    width: 100%;
    height: 400px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }
</style>

<section id="hero" class="d-flex align-items-center">
  <div class="container text-center" data-aos="fade-in" data-aos-delay="200">
    <h1 class="text-white">Detail Informasi Rumah Sakit Kota Lhokseumawe</h1>
  </div>
</section>

<section class="about-info-area section-gap">
  <div class="container">
    <div class="row">
      <div class="col-md-12" data-aos="fade-up">
        <div class="panel panel-info">
          <div class="panel-heading text-center">
            <h2 class="panel-title"><strong>Informasi Rumah Sakit</strong></h2>
          </div>
          <div class="panel-body">
            <table class="table">
              <tr><th>Detail</th></tr>
              <tr><td>Foto</td><td><img src="../produk/<?php echo $foto; ?>" width="300px"></td></tr>
              <tr><td>Nama Rumah Sakit</td><td><?php echo $nama; ?></td></tr>
              <tr><td>Alamat</td><td><?php echo $alamat; ?></td></tr>
              <tr><td>Deskripsi</td><td><?php echo $deskripsi; ?></td></tr>
              <tr><td>Akreditasi</td><td><?php echo $akreditasi; ?></td></tr>
              <tr><td>Tipe Kelas</td><td><?php echo $tipe_kelas; ?></td></tr>
              <tr><td>Spesialis</td><td>
                <ul>
                  <?php foreach ($spesialis_list as $spesialis) { ?>
                    <li><?php echo $spesialis; ?></li>
                  <?php } ?>
                </ul>
              </td></tr>
            </table>
          </div>
        </div>
      </div>
      
      <div class="col-md-12" data-aos="zoom-in">
        <div class="panel panel-info">
          <div class="panel-heading text-center">
            <h2 class="panel-title"><strong>Lokasi</strong></h2>
          </div>
          <button id="getLocationBtn" class="btn btn-primary" style="margin-bottom: 10px;">Dapatkan Lokasi</button>
          <div class="panel-body">
            <div id="map"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
var map = L.map('map').setView([<?php echo $lat; ?>, <?php echo $long; ?>], 13);

// Tambahkan peta default
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Tambahkan marker rumah sakit
var hospitalMarker = L.marker([<?php echo $lat; ?>, <?php echo $long; ?>]).addTo(map)
    .bindPopup("<b><?php echo $nama; ?></b><br><?php echo $alamat; ?>").openPopup();

// Marker lokasi awal
var startMarker = null;
var routingControl = null;

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

// Event klik pada peta untuk memilih lokasi awal
map.on('click', function(e) {
    if (startMarker) {
        map.removeLayer(startMarker);
    }
    
    let startLatLng = e.latlng;
    startMarker = L.marker(startLatLng).addTo(map).bindPopup("Lokasi Awal").openPopup();

    // Hapus rute lama jika ada
    if (routingControl) {
        map.removeControl(routingControl);
    }

    // Tambahkan rute ke rumah sakit
    routingControl = L.Routing.control({
        waypoints: [
            L.latLng(startLatLng.lat, startLatLng.lng),
            L.latLng(<?php echo $lat; ?>, <?php echo $long; ?>)
        ],
        routeWhileDragging: true,
        createMarker: function() { return null; },
        lineOptions: { styles: [{ color: 'blue', weight: 6, opacity: 0.7 }] }
    }).addTo(map);

    document.querySelectorAll('.leaflet-routing-container').forEach(el => el.style.display = 'none');

    // Menambahkan ikon pada daftar petunjuk arah setelah rute ditemukan
    routingControl.on('routesfound', function(e) {
        let routes = e.routes[0];
        let instructions = routes.instructions;
        let directionsList = document.getElementById("directions-list");
        let totalInfo = document.getElementById("total-info");
        directionsList.innerHTML = "";

        instructions.forEach(step => {
            let listItem = document.createElement("li");
            listItem.innerHTML = `${getDirectionIcon(step.text)} ${step.text} (${step.distance} m)`;
            directionsList.appendChild(listItem);
        });

        // Menampilkan total jarak dan estimasi waktu
        let totalDistance = (routes.summary.totalDistance / 1000).toFixed(2); // dalam km
        let totalTime = Math.ceil(routes.summary.totalTime / 60); // dalam menit
        totalInfo.innerHTML = `Total Jarak: ${totalDistance} km | Estimasi Waktu: ${totalTime} menit`;
    });
});

// Fungsi untuk mendapatkan lokasi dari GPS
document.getElementById("getLocationBtn").addEventListener("click", function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            let userLatLng = L.latLng(position.coords.latitude, position.coords.longitude);
            setRoute(userLatLng);
        }, function(error) {
            alert("Gagal mendapatkan lokasi. Pastikan GPS diaktifkan.");
        });
    } else {
        alert("Geolocation tidak didukung oleh browser ini.");
    }
});

function setRoute(startLatLng) {
    // Hapus marker lama jika ada
    if (startMarker) {
        map.removeLayer(startMarker);
    }

    // Tambahkan marker lokasi awal
    startMarker = L.marker(startLatLng).addTo(map).bindPopup("Lokasi Anda").openPopup();

    // Hapus rute lama jika ada
    if (routingControl) {
        map.removeControl(routingControl);
    }

    // Tambahkan rute ke rumah sakit
    routingControl = L.Routing.control({
        waypoints: [
            L.latLng(startLatLng.lat, startLatLng.lng),
            L.latLng(<?php echo $lat; ?>, <?php echo $long; ?>)
        ],
        routeWhileDragging: true,
        createMarker: function() { return null; },
        lineOptions: { styles: [{ color: 'blue', weight: 6, opacity: 0.7 }] }
    }).addTo(map);

    // Sembunyikan UI default dari leaflet routing
    document.querySelectorAll('.leaflet-routing-container').forEach(el => el.style.display = 'none');

    // Tambahkan petunjuk arah ke daftar
    routingControl.on('routesfound', function(e) {
        let routes = e.routes[0];
        let directionsList = document.getElementById("directions-list");
        let totalInfo = document.getElementById("total-info");
        directionsList.innerHTML = "";

        routes.instructions.forEach(step => {
            let listItem = document.createElement("li");
            listItem.innerHTML = `${getDirectionIcon(step.text)} ${step.text} (${step.distance} m)`;
            directionsList.appendChild(listItem);
        });

        // Tampilkan total jarak dan estimasi waktu
        let totalDistance = (routes.summary.totalDistance / 1000).toFixed(2); // dalam km
        let totalTime = Math.ceil(routes.summary.totalTime / 60); // dalam menit
        totalInfo.innerHTML = `Total Jarak: ${totalDistance} km | Estimasi Waktu: ${totalTime} menit`;
    });
}

</script>

<?php include "footer.php"; ?>
