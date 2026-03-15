<!-- Content Header -->
<section class="content-header">
  <h1>
    <i class="fa fa-file-text-o icon-title"></i> Laporan Stok Obat
    
    <div class="pull-right">
      <a class="btn btn-success btn-social" href="modules/lap-stok/excel.php" target="_blank">
        <i class="fa fa-file-excel-o"></i> Excel
      </a>
      <!-- <a class="btn btn-danger btn-social" href="modules/lap-stok/pdf.php" target="_blank">
        <i class="fa fa-file-pdf-o"></i> PDF
      </a> -->
      <a class="btn btn-primary btn-social" onclick="printReport()">
        <i class="fa fa-print"></i> Print
      </a>
    </div>
  </h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-body">
          
          <!-- Form Filter -->
          <!-- Form Filter -->
          <form method="GET" action="" class="form-inline" style="margin-bottom: 20px;">
            <input type="hidden" name="module" value="lap_stok">
            
            <!-- Search Field -->
            <div class="form-group">
              <label>Cari</label>
              <input type="text" name="cari" class="form-control" placeholder="Nama/Kode Obat" 
                value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
            </div>
            
            <div class="form-group" style="margin-left:10px;">
              <label>Bulan</label>
              <select name="bulan" class="form-control">
                <option value="">-- Semua Bulan --</option>
                <?php
                $bulan = array(
                  '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
                  '04' => 'April', '05' => 'Mei', '06' => 'Juni', 
                  '07' => 'Juli', '08' => 'Agustus', '09' => 'September', 
                  '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                );
                
                foreach ($bulan as $key => $value) {
                  $selected = (isset($_GET['bulan']) && $_GET['bulan'] == $key) ? 'selected' : '';
                  echo "<option value='$key' $selected>$value</option>";
                }
                ?>
              </select>
            </div>
            
            <div class="form-group" style="margin-left:10px;">
              <label>Tahun</label>
              <select name="tahun" class="form-control">
                <option value="">-- Semua Tahun --</option>
                <?php
                $current_year = date('Y');
                $future_limit = $current_year + 10; // Menambahkan 10 tahun ke depan
                
                for ($i = $future_limit; $i >= 2020; $i--) {
                  $selected = (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? 'selected' : '';
                  echo "<option value='$i' $selected>$i</option>";
                }
                ?>
              </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="margin-left:10px;">
              <i class="fa fa-filter"></i> Filter
            </button>
            <a href="?module=lap_stok" class="btn btn-default" style="margin-left:10px;">
              <i class="fa fa-refresh"></i> Reset
            </a>
          </form>

          <!-- Tabel Data -->
          <!-- Tabel Data -->
          <table id="dataTables3" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th class="center">No.</th>
                <th class="center">Kode Obat</th>
                <th class="center">Nama Obat</th>
                <th class="center">Satuan</th>
                <th class="center">Sisa Stok</th>
                <th class="center">Jumlah Masuk</th>
                <th class="center">Jumlah Keluar</th>
                <th class="center">Tanggal Masuk</th>
                <th class="center">Expired Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $where = "";
              
              // Filter pencarian
              if (isset($_GET['cari']) && !empty($_GET['cari'])) {
                $search = mysqli_real_escape_string($mysqli, $_GET['cari']);
                $where .= " AND (o.kode_obat LIKE '%$search%' OR o.nama_obat LIKE '%$search%')";
              }
              
              // Filter bulan dan tahun
              if (isset($_GET['bulan']) && !empty($_GET['bulan'])) {
                $where .= " AND MONTH(m.tanggal_masuk) = '".$_GET['bulan']."'";
              }
              
              if (isset($_GET['tahun']) && !empty($_GET['tahun'])) {
                $where .= " AND YEAR(m.tanggal_masuk) = '".$_GET['tahun']."'";
              }
              
              $query = mysqli_query($mysqli, "
                SELECT 
                  o.kode_obat,
                  o.nama_obat,
                  s.nama_satuan,
                  o.stok AS sisa_stok,
                  m.jumlah_masuk,
                  IFNULL(k.total_keluar, 0) AS jumlah_keluar,
                  m.tanggal_masuk,
                  m.expired_date,
                  DATEDIFF(m.expired_date, CURDATE()) AS days_until_expired
                FROM is_view_masuk m
                INNER JOIN is_obat o ON m.kode_obat = o.kode_obat
                INNER JOIN is_satuan AS s ON o.satuan = s.id_satuan
                LEFT JOIN (
                  SELECT kode_obat, expired_date, SUM(jumlah_keluar) AS total_keluar
                  FROM is_obat_keluar
                  GROUP BY kode_obat, expired_date
                ) k ON m.kode_obat = k.kode_obat AND m.expired_date = k.expired_date
                WHERE 1=1 $where
                ORDER BY o.nama_obat ASC, days_until_expired ASC, m.tanggal_masuk ASC
              ") or die(mysqli_error($mysqli));

              $data_obat = [];
              while ($row = mysqli_fetch_assoc($query)) {
                $kode = $row['kode_obat'];
                if (!isset($data_obat[$kode])) {
                  $data_obat[$kode] = [
                    'nama_obat' => $row['nama_obat'],
                    'nama_satuan' => $row['nama_satuan'],
                    'sisa_stok' => $row['sisa_stok'],
                    'detail' => []
                  ];
                }

                $data_obat[$kode]['detail'][] = [
                  'jumlah_masuk' => $row['jumlah_masuk'],
                  'jumlah_keluar' => $row['jumlah_keluar'],
                  'tanggal_masuk' => $row['tanggal_masuk'],
                  'expired_date' => $row['expired_date'],
                  'days_until_expired' => $row['days_until_expired']
                ];
              }

              // Tampilkan pesan jika tidak ada data
              if (empty($data_obat)) {
                echo '<tr><td colspan="9" class="text-center">Tidak ada data yang ditemukan</td></tr>';
              } else {
                foreach ($data_obat as $kode_obat => $obat) {
                  // Urutkan detail berdasarkan days_until_expired (yang paling dekat duluan)
                  usort($obat['detail'], function($a, $b) {
                    return $a['days_until_expired'] - $b['days_until_expired'];
                  });
                  
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
                        <td class='text-right' rowspan='$rowspan'>{$obat['sisa_stok']}</td>
                      ";
                    }

                    echo "
                      <td class='text-right'>{$detail['jumlah_masuk']}</td>
                      <td class='text-right'>{$detail['jumlah_keluar']}</td>
                      <td class='center'>" . date('d-m-Y', strtotime($detail['tanggal_masuk'])) . "</td>
                      <td class='center'>" . date('d-m-Y', strtotime($detail['expired_date'])) . "</td>
                    </tr>";

                    $i++;
                  }
                  $no++;
                }
              }
              ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!--/.col -->
  </div> <!-- /.row -->
</section><!-- /.content -->

<script>
function printReport() {
  // Pass search parameters to print page
  var searchParams = new URLSearchParams(window.location.search);
  var printUrl = 'modules/lap-stok/cetak.php';
  
  // Add search parameters to print URL
  if (searchParams.has('cari')) {
    printUrl += (printUrl.includes('?') ? '&' : '?') + 'cari=' + encodeURIComponent(searchParams.get('cari'));
  }
  if (searchParams.has('bulan')) {
    printUrl += (printUrl.includes('?') ? '&' : '?') + 'bulan=' + encodeURIComponent(searchParams.get('bulan'));
  }
  if (searchParams.has('tahun')) {
    printUrl += (printUrl.includes('?') ? '&' : '?') + 'tahun=' + encodeURIComponent(searchParams.get('tahun'));
  }
  
  window.open(printUrl, '_blank');
}

// Add client-side search functionality for quick filtering
$(document).ready(function() {
  // Initialize DataTables with search functionality
  $('#dataTables3').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "pageLength": 10,
    "language": {
      "search": "Filter cepat:",
      "zeroRecords": "Tidak ada data yang ditemukan",
      "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      "infoEmpty": "Tidak ada data yang tersedia",
      "infoFiltered": "(difilter dari _MAX_ total data)"
    }
  });
});
</script>