<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>WebGIS SPBU - Layer Groups & Control</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --primary-navy: #0F2027; /* Gelap & Elegan */
            --secondary-navy: #203A43;
            --accent-blue: #2C5364;
            --bg-color: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0; 
            background: var(--bg-color); 
            color: var(--text-main);
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-navy), var(--secondary-navy));
            color: white;
            padding: 20px 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .panel, .table-container { 
            background: var(--card-bg); 
            padding: 25px 30px; 
            border-radius: 16px; 
            margin-bottom: 25px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .panel:hover, .table-container:hover {
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        }

        .panel h3, .table-container h3 {
            color: var(--primary-navy);
            font-weight: 700;
            margin-top: 0;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .form-inline { 
            display: flex; 
            gap: 15px; 
            align-items: center; 
            flex-wrap: wrap; 
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            min-width: 200px;
        }

        .input-group strong {
            font-size: 14px;
            color: var(--secondary-navy);
            font-weight: 600;
        }

        input, select { 
            padding: 12px 16px; 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: var(--text-main);
            background-color: var(--bg-color);
            transition: all 0.3s ease;
            outline: none;
            width: 100%;
            box-sizing: border-box;
        }

        input:focus, select:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(44, 83, 100, 0.15);
            background-color: #FFF;
        }

        small {
            color: var(--text-muted);
            font-size: 13px;
            width: 100%;
            display: block;
            margin-top: 5px;
        }

        #map { 
            height: 550px; 
            width: 100%; 
            border-radius: 16px; 
            border: none; 
            box-shadow: 0 10px 25px rgba(15, 32, 39, 0.1); 
            z-index: 1;
            margin-bottom: 25px;
        }

        /* Tabel Styling */
        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
            margin-top: 15px;
        }
        th, td { 
            padding: 16px 20px; 
            text-align: left; 
            border-bottom: 1px solid var(--border-color); 
        }
        th { 
            background: linear-gradient(135deg, var(--primary-navy), var(--secondary-navy)); 
            color: white; 
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.8px;
        }
        th:first-child { border-top-left-radius: 10px; }
        th:last-child { border-top-right-radius: 10px; }
        
        tbody tr {
            transition: background-color 0.2s ease;
        }
        tbody tr:hover {
            background-color: #F1F5F9;
        }

        /* Badges & Icons */
        .badge-24h { 
            background: rgba(16, 185, 129, 0.1); 
            color: #059669; 
            padding: 6px 14px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: 600;
            border: 1px solid rgba(16, 185, 129, 0.2);
            display: inline-block;
        }
        .badge-reg { 
            background: rgba(245, 158, 11, 0.1); 
            color: #D97706; 
            padding: 6px 14px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: 600;
            border: 1px solid rgba(245, 158, 11, 0.2);
            display: inline-block;
        }

        .icon-24h {
            background-color: #10B981;
            border-radius: 50% 50% 50% 0;
            border: 2px solid #fff;
            width: 24px !important; height: 24px !important;
            transform: rotate(-45deg);
            margin-left: -12px; margin-top: -12px;
            box-shadow: 2px 2px 6px rgba(0,0,0,0.3);
            transition: transform 0.2s ease;
        }
        .icon-reg {
            background-color: #F59E0B;
            border-radius: 50% 50% 50% 0;
            border: 2px solid #fff;
            width: 24px !important; height: 24px !important;
            transform: rotate(-45deg);
            margin-left: -12px; margin-top: -12px;
            box-shadow: 2px 2px 6px rgba(0,0,0,0.3);
            transition: transform 0.2s ease;
        }

        /* Leaflet Overrides */
        .leaflet-control-layers, .leaflet-bar {
            border: none !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
            border-radius: 12px !important;
        }
        .leaflet-control-layers { padding: 8px !important; font-family: 'Inter', sans-serif; }
        .leaflet-bar { overflow: hidden; }
        .leaflet-bar a {
            border-bottom: 1px solid #eee !important;
            color: var(--primary-navy) !important;
            width: 32px !important;
            height: 32px !important;
            line-height: 32px !important;
        }
        .leaflet-bar a:hover {
            background-color: #f8f9fa !important;
            color: var(--accent-blue) !important;
        }
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .leaflet-popup-content { font-family: 'Inter', sans-serif; font-size: 14px; line-height: 1.5; }
        .leaflet-popup-content b { color: var(--primary-navy); font-size: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>📍 Manajemen SPBU (Point)</h2>
    </div>

    <div class="container">

    <div class="panel">
        <h3>Input Data Baru</h3>
        <div class="form-inline">
            <div class="input-group">
                <strong>Nama SPBU:</strong>
                <input type="text" id="nama_spbu" placeholder="Masukkan Nama SPBU">
            </div>
            <div class="input-group">
                <strong>Alamat:</strong>
                <input type="text" id="alamat_spbu" placeholder="Masukkan Alamat">
            </div>
            <div class="input-group">
                <strong>Jenis Layanan:</strong>
                <select id="jenis_layanan">
                    <option value="24 Jam">Buka 24 Jam</option>
                    <option value="Reguler">Reguler (Tidak 24 Jam)</option>
                </select>
            </div>
        </div>
        <small>Pilih jenis layanan, lalu gunakan ikon marker di kiri peta untuk meletakkan titik.</small>
    </div>

    <div id="map"></div>

    <div class="table-container">
        <h3>Data Informasi</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama SPBU</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Koordinat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM data_spbu ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($res)) {
                    $badge = ($row['jenis_layanan'] == '24 Jam') ? 'badge-24h' : 'badge-reg';
                    echo "<tr>
                            <td>{$row['nama_spbu']}</td>
                            <td>{$row['alamat']}</td>
                            <td><span class='$badge'>{$row['jenis_layanan']}</span></td>
                            <td>{$row['latitude']}, {$row['longitude']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script>
        // 1. Setup Peta & Base Maps
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OSM' });
        var sat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Esri' });

        var map = L.map('map', {
            center: [-0.02, 109.34],
            zoom: 13,
            layers: [osm]
        });

        // 2. Definisi Layer Groups
        var group24Jam = L.layerGroup().addTo(map);
        var groupReguler = L.layerGroup().addTo(map);

        // 3. Layers Control (Checkbox di Pojok Kanan Atas)
        var baseMaps = { "Peta Standar": osm, "Satelit": sat };
        var overlayMaps = { 
            "<span style='color:green'>SPBU 24 Jam</span>": group24Jam, 
            "<span style='color:orange'>SPBU Reguler</span>": groupReguler 
        };
        L.control.layers(baseMaps, overlayMaps, { collapsed: false }).addTo(map);

        // 4. Custom Icons
        var icon24h = L.divIcon({className: 'icon-24h'});
        var iconReg = L.divIcon({className: 'icon-reg'});

        // 5. Input Data (Drawing)
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        new L.Control.Draw({
            draw: { polyline:false, polygon:false, circle:false, rectangle:false, circlemarker:false, marker:true }
        }).addTo(map);

        map.on(L.Draw.Event.CREATED, function (e) {
            var layer = e.layer;
            var ll = layer.getLatLng();
            var nama = document.getElementById('nama_spbu').value;
            var alamat = document.getElementById('alamat_spbu').value;
            var jenis = document.getElementById('jenis_layanan').value;

            if(!nama || !alamat) return alert("Lengkapi data!");

            var fd = new FormData();
            fd.append('nama', nama); fd.append('alamat', alamat);
            fd.append('lat', ll.lat); fd.append('lng', ll.lng); fd.append('jenis', jenis);

            fetch('save.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') location.reload();
            });
        });

        // 6. Tampilkan Marker dari Database dengan Warna Berbeda
        <?php
        $res = mysqli_query($conn, "SELECT * FROM data_spbu");
        while($row = mysqli_fetch_assoc($res)) {
            $j = $row['jenis_layanan'];
            $target = ($j == '24 Jam') ? 'group24Jam' : 'groupReguler';
            $icon = ($j == '24 Jam') ? 'icon24h' : 'iconReg';
            
            echo "L.marker([{$row['latitude']}, {$row['longitude']}], {icon: $icon}).addTo($target)
                    .bindPopup('<b>{$row['nama_spbu']}</b><br>{$row['alamat']}');\n";
        }
        ?>
    </script>
    </div>
</body>
</html>