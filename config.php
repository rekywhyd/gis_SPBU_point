<?php
$conn = mysqli_connect("localhost", "root", "", "gis_point");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }
?>