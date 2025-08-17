
<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$name = "sistem_rs";

$koneksi = mysqli_connect($host, $user, $pass, $name);
if (mysqli_connect_errno()) {
    echo "Koneksi database mysqli gagal!!! : " . mysqli_connect_error();
}

$sql = "SELECT a.id, a.nama, a.alamat, a.foto, r.nilai 
        FROM ranking r
        JOIN rumah_sakit a ON r.id_alternatif = a.id
        ORDER BY r.nilai DESC";

$result = $koneksi->query($sql);
$peringkat = 1;

//mysqli_select_db($name, $koneksi) or die("Tidak ada database yang dipilih!");
