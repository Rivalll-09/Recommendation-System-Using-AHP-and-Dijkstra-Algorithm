<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sistem Pemetaan RS</title>
  <link href="../assets/unimal.png" rel="icon">
  <link href="../assets/unimal.png" rel="apple-touch-icon">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
  <script src="https://unpkg.com/leaflet-providers"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
    #map {
        width: 90vw; /* Gunakan 90% dari viewport width */
        max-width: 1200px; /* Batasi ukuran maksimal */
        height: 70vh; /* Gunakan 70% dari tinggi layar */
        min-height: 400px; /* Pastikan tidak terlalu kecil */
        margin: 20px auto; /* Pusatkan */
        border-radius: 10px; /* Sudut membulat */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Efek bayangan */
    }

    @media (max-width: 768px) {
        #map {
            width: 95vw; /* Sedikit lebih lebar di mobile */
            height: 50vh; /* Ukuran lebih kecil untuk mobile */
        }
    }
  </style>
</head>
<body>
<header>
    <?php include "header.php"; ?>
</header>

<section id="hero" class="d-flex align-items-center">
    <div class="container text-center position-relative" data-aos="fade-in" data-aos-delay="200">
        <h1>Djikstra Rumah Sakit</h1>
    </div>
</section>  
<section id="proker" class="proker">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="menu-content col-lg-12">
                <div class="title text-center">
                    <div id="control-panel" class="rounded shadow p-3">
                        <select id="gampongSelect" class="form-select mb-2">
                            <option value="">Pilih Gampong</option>
                        </select>
                        <select id="spesialisSelect" class="form-select mb-2">
                            <option value="">Pilih Spesialis</option>
                        </select>
                        <button id="useDeviceLocation" class="btn btn-primary">Gunakan Lokasi Perangkat</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container d-flex justify-content-center">
    <div id="map"></div>
</div>


<script src="../assets/js/peta.js"></script>

<footer>
    <?php include "footer.php"; ?>
</footer>

</body>
</html>
