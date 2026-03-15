<section class="content-header">
    <h1>Stok Obat Menipis</h1>
</section>

<section class="content">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Daftar Stok Obat</h3>
        </div>

        <div class="box-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Obat</th>
                        <th>Nama Obat</th>
                        <th>Stok</th>
                        <th>Expired Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // $query = mysqli_query($mysqli, "SELECT o.kode_obat, o.nama_obat, o.stok,
                    //         MIN(m.expired_date) AS expired_date
                    //     FROM 
                    //         is_obat o
                    //     LEFT JOIN 
                    //         is_obat_masuk m ON o.kode_obat = m.kode_obat
                    //     WHERE 
                    //         o.stok < 21
                    //     GROUP BY 
                    //         o.kode_obat, o.nama_obat, o.stok
                    //     ORDER BY 
                    //         o.stok ASC
                    // ") or die("Query Error: " . mysqli_error($mysqli));

                    $query = mysqli_query($mysqli, "SELECT 
                            o.kode_obat, 
                            o.nama_obat, 
                            o.stok,
                            m.expired_date,
                            IFNULL((
                                SELECT MAX(jumlah_masuk) 
                                FROM is_obat_masuk 
                                WHERE kode_obat = o.kode_obat
                            ), 0) AS max_masuk,
                            CASE 
                                WHEN (
                                    SELECT MAX(jumlah_masuk) 
                                    FROM is_obat_masuk 
                                    WHERE kode_obat = o.kode_obat
                                ) >= 1001 THEN 101
                                WHEN (
                                    SELECT MAX(jumlah_masuk) 
                                    FROM is_obat_masuk 
                                    WHERE kode_obat = o.kode_obat
                                ) >= 101 THEN 51
                                ELSE 21
                            END AS batas
                        FROM 
                            is_obat o
                        LEFT JOIN 
                            (SELECT kode_obat, MIN(expired_date) AS expired_date FROM is_obat_masuk GROUP BY kode_obat) m
                            ON o.kode_obat = m.kode_obat
                        HAVING 
                            o.stok < batas
                        ORDER BY 
                            o.stok ASC") 
                        or die("Query Error: " . mysqli_error($mysqli));

                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) {
                        $expired = $data['expired_date'] ? date('d-m-Y', strtotime($data['expired_date'])) : '-';
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data['kode_obat']; ?></td>
                        <td><?php echo $data['nama_obat']; ?></td>
                        <td style='color: red; font-weight: bold;'><?php echo $data['stok']; ?></td>
                        <td><?php echo $expired; ?></td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</section>
