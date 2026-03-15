<?php
require_once "../../config/database.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Stok_Obat_".date('Ymd').".xls");

// Filter data
$where = "";
if (isset($_GET['bulan']) && !empty($_GET['bulan'])) {
    $where .= " AND MONTH(m.tanggal_masuk) = '".mysqli_real_escape_string($mysqli, $_GET['bulan'])."'";
}
if (isset($_GET['tahun']) && !empty($_GET['tahun'])) {
    $where .= " AND YEAR(m.tanggal_masuk) = '".mysqli_real_escape_string($mysqli, $_GET['tahun'])."'";
}

// Query data
$query = mysqli_query($mysqli, "
    SELECT 
        o.kode_obat,
        o.nama_obat,
        s.nama_satuan,
        o.stok AS sisa_stok,
        m.jumlah_masuk,
        IFNULL(k.total_keluar, 0) AS jumlah_keluar,
        m.tanggal_masuk,
        m.expired_date
    FROM is_view_masuk m
    INNER JOIN is_obat o ON m.kode_obat = o.kode_obat
    INNER JOIN is_satuan AS s ON o.satuan = s.id_satuan
    LEFT JOIN (
        SELECT kode_obat, expired_date, SUM(jumlah_keluar) AS total_keluar
        FROM is_obat_keluar
        GROUP BY kode_obat, expired_date
    ) k ON m.kode_obat = k.kode_obat AND m.expired_date = k.expired_date
    WHERE 1=1 $where
    ORDER BY o.nama_obat ASC, m.tanggal_masuk ASC
") or die("Error: " . mysqli_error($mysqli));

// Mulai output Excel
echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
    <style>
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; border: 1px solid #ddd; padding: 8px; }
        td { border: 1px solid #ddd; padding: 8px; }
        .center { text-align: center; }
        .right { text-align: right; }
    </style>
</head>
<body>';

// Header Laporan
echo '<div class="title">Perseidian Obat Poli</div>';
echo '<div class="subtitle">Laporan Stok Obat</div>';

// Informasi Filter
echo '<table>
        <tr>
            <td><strong>Bulan</strong></td>
            <td>'.(isset($_GET['bulan']) && !empty($_GET['bulan']) ? date('F', mktime(0, 0, 0, $_GET['bulan'], 10)) : 'Semua Bulan').'</td>
            <td><strong>Tahun</strong></td>
            <td>'.(isset($_GET['tahun']) && !empty($_GET['tahun']) ? $_GET['tahun'] : date('Y')).'</td>
        </tr>
      </table><br>';

// Process data with total calculations
$data_obat = [];
while ($row = mysqli_fetch_assoc($query)) {
    $kode = $row['kode_obat'];
    if (!isset($data_obat[$kode])) {
        $data_obat[$kode] = [
            'nama_obat' => $row['nama_obat'],
            'nama_satuan' => $row['nama_satuan'],
            'sisa_stok' => $row['sisa_stok'],
            'total_masuk' => 0,
            'total_keluar' => 0,
            'detail' => []
        ];
    }

    $data_obat[$kode]['total_masuk'] += $row['jumlah_masuk'];
    $data_obat[$kode]['total_keluar'] += $row['jumlah_keluar'];
    
    $data_obat[$kode]['detail'][] = [
        'jumlah_masuk' => $row['jumlah_masuk'],
        'jumlah_keluar' => $row['jumlah_keluar'],
        'tanggal_masuk' => $row['tanggal_masuk'],
        'expired_date' => $row['expired_date']
    ];
}

// Tabel Data with new columns
echo '<table>
        <tr>
            <th>No.</th>
            <th>Kode Obat</th>
            <th>Nama Obat</th>
            <th>Satuan</th>
            <th>Sisa Stok</th>
            <th>Total Masuk</th>
            <th>Total Keluar</th>
            <th>Jumlah Masuk</th>
            <th>Jumlah Keluar</th>
            <th>Tanggal Masuk</th>
            <th>Expired Date</th>
        </tr>';

$no = 1;
foreach ($data_obat as $kode_obat => $obat) {
    $rowspan = count($obat['detail']);
    $i = 0;

    foreach ($obat['detail'] as $detail) {
        echo "<tr>";
        if ($i === 0) {
            echo "
                <td class='center' rowspan='$rowspan'>{$no}</td>
                <td class='center' rowspan='$rowspan'>{$kode_obat}</td>
                <td rowspan='$rowspan'>{$obat['nama_obat']}</td>
                <td class='center' rowspan='$rowspan'>{$obat['nama_satuan']}</td>
                <td class='right' rowspan='$rowspan'>{$obat['sisa_stok']}</td>
                <td class='right' rowspan='$rowspan'>{$obat['total_masuk']}</td>
                <td class='right' rowspan='$rowspan'>{$obat['total_keluar']}</td>
            ";
        }

        echo "
            <td class='right'>{$detail['jumlah_masuk']}</td>
            <td class='right'>{$detail['jumlah_keluar']}</td>
            <td class='center'>" . date('d-m-Y', strtotime($detail['tanggal_masuk'])) . "</td>
            <td class='center'>" . date('d-m-Y', strtotime($detail['expired_date'])) . "</td>
        </tr>";

        $i++;
    }
    $no++;
}

echo '</table>';

// Footer
echo '<div style="margin-top: 20px; font-size: 12px;">
        <strong>Catatan:</strong> Laporan ini dihasilkan secara otomatis pada '.date('d-m-Y H:i:s').'
      </div>';

echo '</body></html>';
exit;
?>