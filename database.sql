DROP DATABASE IF EXISTS gis_point;
CREATE DATABASE gis_point;
USE gis_point;

CREATE TABLE data_spbu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_spbu VARCHAR(255) NOT NULL,
    alamat TEXT NOT NULL,
    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    jenis_layanan ENUM('24 Jam', 'Reguler') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);