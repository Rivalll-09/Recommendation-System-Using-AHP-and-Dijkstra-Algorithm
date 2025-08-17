<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sistem Pemetaan RS</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/unimal.png" rel="icon">
  <link href="../assets/unimal.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="../assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.3/leaflet.js"></script>
  <script src="https://cdn.maptiler.com/mapbox-gl-js/v1.5.1/mapbox-gl.js"></script>
  <script src="https://cdn.maptiler.com/mapbox-gl-leaflet/latest/leaflet-mapbox-gl.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.3/leaflet.css" />
  <link rel="stylesheet" href="https://cdn.maptiler.com/mapbox-gl-js/v1.5.1/mapbox-gl.css" />
</head>

<body>
  <?php include "header.php"; ?>

  <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
    <div class="container text-center position-relative" data-aos="fade-in" data-aos-delay="200">
        <h1>Selamat Datang di Website</h1>
        <h1>Sistem Pemetaan Rumah Sakit</h1>
        <h1>Kota Lhokseumawe</h1>
        <a href="#sejarah" class="btn-get-started scrollto">Mulai</a>
      </div>
    </section><!-- End Hero -->

    <main id="main">

    <section id="sejarah" class="sejarah">
      <div class="container"> <!-- Ganti <class="container"> menjadi <div class="container"> -->
        <div class="row d-flex justify-content-center">
          <div class="menu-content col-lg-12">
            <div class="title text-center">
              <h2 class="mb-10" data-aos="fade-right" data-aos-delay="100">Rumah Sakit</h2>
              <p data-aos="fade-left" data-aos-delay="200" style="text-align: justify; max-width: 100%; margin: 0 auto;">
              Rumah sakit adalah institusi pelayanan kesehatan yang menyediakan layanan medis secara menyeluruh, termasuk rawat jalan, rawat inap, dan penanganan gawat darurat. Selain sebagai tempat pengobatan, rumah sakit berperan sebagai pusat pendidikan dan pelatihan bagi tenaga kesehatan, serta sebagai lokasi penelitian dan pengembangan ilmu medis untuk meningkatkan kualitas pelayanan kesehatan. Dengan memanfaatkan kemajuan teknologi dan ilmu pengetahuan, rumah sakit berkomitmen memberikan pelayanan yang aman, efektif, dan berorientasi pada kesejahteraan pasien. Rumah sakit juga menjadi bagian penting dalam komunitas, mendukung upaya peningkatan derajat kesehatan masyarakat, serta menyediakan fasilitas dan layanan yang sesuai dengan tingkat kebutuhan, mulai dari layanan dasar hingga spesialis dan subspesialis.
              </p>
              <p data-aos="fade-left" data-aos-delay="200" style="text-align: justify; max-width: 100%; margin: 0 auto;">
              Tidak hanya sebagai tempat pengobatan, rumah sakit juga berfungsi sebagai pusat pendidikan dan pelatihan bagi mahasiswa kedokteran dan tenaga kesehatan lainnya, mencetak profesional yang kompeten untuk mendukung sektor kesehatan. Di samping itu, rumah sakit menjadi tempat penelitian dan pengembangan ilmu pengetahuan medis yang bertujuan meningkatkan kualitas pelayanan kesehatan dan menemukan solusi inovatif untuk tantangan kesehatan yang terus berkembang.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
        <div class="row content">
          <div class="col-lg-12" data-aos="fade-right" data-aos-delay="100">
            <h2>Rumah Sakit Kota Lhokseumawe</h2>
            <p>Berikut Ini merupakan pemetaan rumah sakit yang berada di Kota Lhokseumawe</p>
          </div>
        </div>
        <div id="map"></div>
      </div>
    </section><!-- End About Section -->

      <script>
      var map = L.map('map').setView([5.200730296150841, 97.11048761714383], 13);

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
          "Terrain": L.tileLayer('https://stamen-tiles.a.ssl.fastly.net/terrain/{z}/{x}/{y}.jpg', {
              attribution: 'Map tiles by Stamen Design'
          }),
          "Google Maps": L.tileLayer('https://mt0.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                attribution: '© Google Maps'
          }),
      };

      // Tambahkan default layer ke peta
      baseMaps["Terrain"].addTo(map);

      // Tambahkan kontrol untuk memilih layer
      L.control.layers(baseMaps).addTo(map);

      // Tambahkan MapBoxGL
      var gl = L.mapboxGL({
          attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
          accessToken: 'not-needed',
          style: 'https://api.maptiler.com/maps/streets/style.json?key=1h7e1Z9S76PGy92uF3c5'
      }).addTo(map);

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

      // Ambil data dari PHP
      fetch('marker_conn.php')
          .then(response => response.json())
          .then(data => {
              data.forEach(marker => {
                  addMarker(marker.latitude, marker.longtitude, marker.nama, marker.alamat, marker.foto);
              });
          })
          .catch(error => console.error('Error:', error));
  </script>

      <!-- ======= Tabel Proker ======= -->
         <section id="portfolio" class="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-3" data-aos="fade-right">
                    <div class="section-title">
                        <h2>Daftar Rumah Sakit</h2>
                        <p>Halaman ini memuat Daftar Rumah Sakit Kota Lhokseumawe</p>
                    </div>
                </div>
                <div class="col-lg-9" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-md-12">
                        <div class="panel panel-info panel-dashboard">
                            <div class="panel-heading centered"></div>
                            <div class="panel-body">
                              <div class="table-responsive">
                                <div style="overflow-x: auto;">
                                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <table class="table table-bordered table-striped table-admin">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th width="20%">Foto</th>
                                                <th width="20%">Nama Rumah Sakit</th>
                                                <th width="20%">Alamat</th>
                                                <th width="15%">Detail Rumah Sakit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $data = file_get_contents('http://localhost/skripsi/page_user/ambildata.php');
                                            $no = 1;
                                            if (json_decode($data, true)) {
                                                $obj = json_decode($data);
                                                foreach ($obj->results as $item) {
                                                  ?>
                                                    <tr>
                                                        <td><?php echo $no; ?></td>
                                                        <td><img src="../image/<?php echo $item->foto; ?>" alt="" class="img-fluid"></td>
                                                        <td><?php echo $item->nama; ?></td>
                                                        <td><?php echo $item->alamat; ?></td>
                                                        <td class="ctr">
                                                            <div class="btn-group">
                                                                <a href="detail.php?id=<?php echo $item->id; ?>" rel="tooltip" data-original-title="Lihat File" data-placement="top" class="btn btn-primary">
                                                                    <i class="fa fa-map-marker"></i> Detail dan Lokasi
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $no++;
                                                  }
                                                } else {
                                                echo "Data tidak ada.";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    </main><!-- End #main -->
    <!-- ======= Footer ======= -->
    <?php include "footer.php"; ?>
</body>

</html>