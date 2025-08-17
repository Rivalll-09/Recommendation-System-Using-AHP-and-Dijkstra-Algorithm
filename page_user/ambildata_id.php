<?php
include "../koneksi.php";

$id = $_GET['id']; // Ambil ID RS dari URL

// Query untuk mendapatkan data rumah sakit
$Q = mysqli_query($koneksi, "SELECT * FROM rumah_sakit WHERE id = $id") or die(mysqli_error($koneksi));
$posts = array();

if (mysqli_num_rows($Q)) {
    while ($post = mysqli_fetch_assoc($Q)) {
        $posts[] = $post;
    }
}

// Query untuk mendapatkan daftar spesialis di RS tertentu
$Q2 = mysqli_query($koneksi, "
    SELECT s.nama_spesialis 
    FROM master_rs m
    JOIN spesialis s ON m.id_spesialis = s.id_spesialis
    WHERE m.id_rs = $id
") or die(mysqli_error($koneksi));

$spesialis = array();
if (mysqli_num_rows($Q2)) {
    while ($row = mysqli_fetch_assoc($Q2)) {
        $spesialis[] = $row['nama_spesialis'];
    }
}

// Gabungkan data rumah sakit dengan daftar spesialis
$data = json_encode(array('results' => $posts, 'spesialis' => $spesialis));
?>
