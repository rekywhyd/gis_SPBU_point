<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $jenis = $_POST['jenis'];

    $query = "INSERT INTO data_spbu (nama_spbu, alamat, latitude, longitude, jenis_layanan) 
              VALUES ('$nama', '$alamat', '$lat', '$lng', '$jenis')";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>