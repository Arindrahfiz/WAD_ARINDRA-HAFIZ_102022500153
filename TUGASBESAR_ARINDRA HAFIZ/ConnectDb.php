<?php
//Connect ke database
$koneksi = mysqli_connect("localhost", "root", "", "db_inventaris");

//Hapus Data
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM vendors WHERE id = $id");
    header("Location: index.php");
    exit;
}

//Baca Data Vendor
$hasil  = mysqli_query($koneksi, "SELECT * FROM vendors ORDER BY id DESC");
$jumlah = mysqli_num_rows($hasil);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Vendor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="header">
        <h1>Vendor</h1>
        <p>Kelola data vendor, status legalitas, serta kontak PIC pemasok gudang.</p>
    </div>

    <div class="isi">

        <?php if ($jumlah == 0) { ?>

            <!--Empty State karena masih kosong-->
            <div class="kosong">
                <h2>Belum ada data vendor</h2>
                <p>Mulai daftarkan vendor pertama Anda untuk mengelola pengadaan gudang.</p>
                <a href="StrukturDanLogika.php" class="tombol tombol-utama">Tambah Data Vendor</a>
            </div>

        <?php } else { ?>

            <a href="StrukturDanLogika.php" class="tombol tombol-utama">+ Tambah Data Vendor</a>

            <table>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Perusahaan & Kategori</th>
                    <th>Kota</th>
                    <th>PIC</th>
                    <th>Telepon PIC</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($hasil)) {
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td>VND-<?php echo str_pad($row['id'], 3, "0", STR_PAD_LEFT); ?></td>
                    <td>
                        <b><?php echo htmlspecialchars($row['nama_perusahaan']); ?></b><br>
                        <?php echo htmlspecialchars($row['kategori']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['kota']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_pic']); ?></td>
                    <td><?php echo htmlspecialchars($row['telepon_pic']); ?></td>
                    <td>
                        <?php if ($row['status'] == 1) { ?>
                            <span class="aktif">Aktif</span>
                        <?php } else { ?>
                            <span class="nonaktif">Nonaktif</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="StrukturDanLogika.php?id=<?php echo $row['id']; ?>" class="tombol tombol-abu tombol-kecil">Edit</a>
                        <a href="StrukturDanLogika.php?hapus=<?php echo $row['id']; ?>" class="tombol tombol-merah tombol-kecil"
                           onclick="return confirm('Yakin ingin menghapus vendor ini?')">Hapus</a>
                    </td>
                </tr>
                <?php
                    $no++;
                }
                ?>
            </table>

        <?php } ?>

    </div>

</body>
</html>