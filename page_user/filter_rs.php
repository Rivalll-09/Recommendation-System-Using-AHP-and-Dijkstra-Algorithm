<?php
include 'ranking.php';

$id_spesialis = isset($_POST['id_spesialis']) ? $_POST['id_spesialis'] : '';

// Buat query untuk mengambil rumah sakit berdasarkan spesialis jika dipilih
if (!empty($id_spesialis)) {
    $sql = "SELECT DISTINCT a.id, a.nama, a.alamat, a.foto, a.akreditasi, a.tipe_kelas, r.nilai 
            FROM ranking r
            JOIN rumah_sakit a ON r.id_alternatif = a.id
            JOIN master_rs m ON a.id = m.id_rs
            WHERE m.id_spesialis = ?
            ORDER BY r.nilai DESC";

    
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_spesialis);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Jika tidak ada spesialis yang dipilih, tampilkan semua rumah sakit
    $sql = "SELECT a.id, a.nama, a.alamat, a.foto, a.akreditasi, a.tipe_kelas, r.nilai 
            FROM ranking r
            JOIN rumah_sakit a ON r.id_alternatif = a.id
            ORDER BY r.nilai DESC";
    
    $result = $koneksi->query($sql);
}

$peringkat = 1;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <img src="../image/<?php echo $row['foto']; ?>" class="card-img-top img-fluid" alt="Foto Rumah Sakit">
                <div class="card-body">
                    <h5 class="card-title"> <?php echo $row['nama']; ?></h5>
                    <p class="card-text"><strong>🏆 Akreditasi:</strong> <?php echo $row['akreditasi']; ?></p>
                    <p class="card-text"><strong>🏥 Tipe Kelas:</strong> <?php echo $row['tipe_kelas']; ?></p>
                    <p class="card-text"><strong>📍 Lokasi:</strong> <?php echo $row['alamat']; ?></p>
                    <p class="card-text"><strong>🏅 Peringkat:</strong> #<?php echo $peringkat; ?></p>
                    <p class="card-text"><strong>⭐ Nilai:</strong> <?php echo number_format($row['nilai'], 2); ?></p>
                    <?php if (isset($row['id'])) { ?>
                        <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn btn-success">
                            <i class="fas fa-map-marker-alt"></i> Detail & Lokasi
                        </a>
                    <?php } else { ?>
                        <p class="text-danger">ID tidak ditemukan</p>
                    <?php } ?>

                </div>
            </div>
        </div>
<?php
        $peringkat++;
    }
} else {
    echo "<div class='col-12 text-center'>
            <div class='alert alert-warning'>
                <i class='fas fa-exclamation-triangle'></i> Tidak ada rumah sakit dengan spesialis yang dipilih.
            </div>
          </div>";
}

$koneksi->close();
?>
