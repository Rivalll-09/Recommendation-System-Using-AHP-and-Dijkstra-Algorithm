<?php
include 'ranking.php';

// Ambil daftar spesialis dari database
$sql_spesialis = "SELECT id_spesialis, nama_spesialis FROM spesialis";
$result_spesialis = $koneksi->query($sql_spesialis);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sistem Pemetaan RS</title>
  <link href="../assets/unimal.png" rel="icon">
  <link href="../assets/unimal.png" rel="apple-touch-icon">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
  <style>
    .card:hover {
        transform: scale(1.05);
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    #loading {
        display: none;
        text-align: center;
        font-size: 1.5rem;
        margin-top: 20px;
    }
  </style>
</head>
<body>

<header>
    <?php include "header.php"; ?>
</header>

<section id="hero" class="d-flex align-items-center">
    <div class="container text-center position-relative" data-aos="fade-in" data-aos-delay="200">
        <h1>Rekomendasi Rumah Sakit</h1>
    </div>
</section>  

<!-- Filter Spesialis -->
<section id="filter" class="py-4 bg-light">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <label for="spesialis" class="h5">🔍 Pilih Spesialis:</label>
                <select id="spesialis" class="form-control">
                    <option value="">-- Semua Spesialis --</option>
                    <?php while ($row_spesialis = $result_spesialis->fetch_assoc()) { ?>
                        <option value="<?php echo $row_spesialis['id_spesialis']; ?>">
                            <?php echo $row_spesialis['nama_spesialis']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div id="loading">
            <i class="fas fa-spinner fa-spin"></i> Memuat data...
        </div>
    </div>
</section>

<!-- Daftar Rumah Sakit -->
<section id="portfolio" class="contact py-5">
    <div class="container">
        <div class="row" id="rs-list">
            <?php include 'filter_rs.php'; ?>
        </div>
    </div>
</section>

<footer>
    <?php include "footer.php"; ?>
</footer>

<script>
$(document).ready(function () {
    $("#spesialis").change(function () {
        var id_spesialis = $(this).val();
        $("#loading").show(); // Tampilkan loading

        $.ajax({
            url: "filter_rs.php",
            method: "POST",
            data: { id_spesialis: id_spesialis },
            success: function (data) {
                $("#rs-list").html(data);
                $("#loading").hide(); // Sembunyikan loading
            }
        });
    });
});
</script>

</body>
</html>
