<?php
include '../koneksi.php'; // Pastikan file ini berisi koneksi ke database

header('Content-Type: application/json');

if (isset($_GET['id_spesialis'])) {
    $id_spesialis = intval($_GET['id_spesialis']);

    $sql = "SELECT rs.nama, rs.alamat, rs.latitude, rs.longtitude 
            FROM master_rs m 
            JOIN rumah_sakit rs ON m.id_rs = rs.id
            WHERE m.id_spesialis = ?";

    $stmt = $koneksi->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_spesialis);
        $stmt->execute();
        $result = $stmt->get_result();

        $rumah_sakit = [];
        while ($row = $result->fetch_assoc()) {
            $rumah_sakit[] = $row;
        }

        echo json_encode($rumah_sakit);
    } else {
        echo json_encode(["error" => "Query gagal dijalankan"]);
    }
} else {
    echo json_encode(["error" => "Parameter id_spesialis tidak ditemukan"]);
}
?>
