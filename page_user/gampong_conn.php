<?php
include '../koneksi.php'; // Pastikan file koneksi benar

$sql = "SELECT nama_gampong, latitude, longtitude FROM gampong_lsm";
$result = $koneksi->query($sql);

$gampong = [];
while ($row = $result->fetch_assoc()) {
    $gampong[] = $row;
}

echo json_encode($gampong);
$koneksi->close();
?>
