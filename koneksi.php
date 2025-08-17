<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$name = "sistem_rs";

$koneksi = mysqli_connect($host, $user, $pass, $name);
if (mysqli_connect_errno()) {
    echo "Koneksi database mysqli gagal!!! : " . mysqli_connect_error();
}
//mysqli_select_db($name, $koneksi) or die("Tidak ada database yang dipilih!");
