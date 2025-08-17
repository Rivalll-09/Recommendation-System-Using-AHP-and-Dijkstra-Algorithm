<?php
include '../koneksi.php'; // Pastikan file ini berisi koneksi ke database

$sql = "SELECT id_spesialis, nama_spesialis FROM spesialis";
$result = $koneksi->query($sql);

$spesialis = [];

while ($row = $result->fetch_assoc()) {
    $spesialis[] = $row;
}

echo json_encode($spesialis);
$koneksi->close();
?>
