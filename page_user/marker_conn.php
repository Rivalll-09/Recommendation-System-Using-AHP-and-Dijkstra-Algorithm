<?php
// Koneksi ke database
$host = "127.0.0.1";
$user = "root";
$password = "";
$dbname = "sistem_rs";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari tabel
$sql = "SELECT nama, alamat, latitude, longtitude, foto FROM rumah_sakit";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Tambahkan jalur ke folder "images/" jika foto hanya berupa nama file
        $row['foto'] = "../image/" . $row['foto'];
        $data[] = $row;
    }
}

// Kembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
