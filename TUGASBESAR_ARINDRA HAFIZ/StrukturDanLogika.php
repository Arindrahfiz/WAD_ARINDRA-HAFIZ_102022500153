<?php
//Connect ke database
$koneksi = mysqli_connect("localhost", "root", "", "db_inventaris");

//Cek Data ada atau nggak, kalo ada tambah, jika tidak edit atau hapus
$id = 0;
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
}

//Daftar Kolom
$kolom = array('nama_perusahaan', 'kategori', 'email', 'telepon', 'alamat',
               'kota', 'npwp', 'nib', 'nama_pic', 'telepon_pic');

//State kolom
$data = array();
foreach ($kolom as $k) {
    $data[$k] = "";
}
$data['status'] = 1;

//Untuk Edit mode
if ($id > 0) {
    $hasil = mysqli_query($koneksi, "SELECT * FROM vendors WHERE id = $id");
    $row = mysqli_fetch_assoc($hasil);
    if ($row) {
        $data = $row;
    }
}

//Cek kondisi Form
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Ambil data
    foreach ($kolom as $k) {
        $data[$k] = trim($_POST[$k]);
    }
    $data['status'] = isset($_POST['status']) ? 1 : 0;

    //Validasi data
    if ($data['nama_perusahaan'] == "" || $data['alamat'] == "" || $data['nama_pic'] == "") {
        $error = "Nama perusahaan, alamat, dan nama PIC wajib diisi.";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "format email wajib menggunakan @ atau contoh arindrahafizah@telkom.ac.id ";
    }

    //Kalau gk ada error simpan ke DB
    if ($error == "") {

        //Simpan Data
        $v = array();
        foreach ($kolom as $k) {
            $v[$k] = mysqli_real_escape_string($koneksi, $data[$k]);
        }
        $status = $data['status'];

        if ($id > 0) {
            //Update data di DB
            $sql = "UPDATE vendors SET
                        nama_perusahaan = '$v[nama_perusahaan]',
                        kategori        = '$v[kategori]',
                        email           = '$v[email]',
                        telepon         = '$v[telepon]',
                        alamat          = '$v[alamat]',
                        kota            = '$v[kota]',
                        npwp            = '$v[npwp]',
                        nib             = '$v[nib]',
                        nama_pic        = '$v[nama_pic]',
                        telepon_pic     = '$v[telepon_pic]',
                        status          = $status
                    WHERE id = $id";
        } else {
            //Tambah data
            $sql = "INSERT INTO vendors
                        (nama_perusahaan, kategori, email, telepon, alamat, kota, npwp, nib, nama_pic, telepon_pic, status)
                    VALUES
                        ('$v[nama_perusahaan]', '$v[kategori]', '$v[email]', '$v[telepon]', '$v[alamat]',
                         '$v[kota]', '$v[npwp]', '$v[nib]', '$v[nama_pic]', '$v[telepon_pic]', $status)";
        }

        mysqli_query($koneksi, $sql);
        header("Location: connectDb.php");
        exit;
    }
}

//Pilihan Kategori
$daftar_kategori = array(
    'Pemasok Kemasan',
    'Pemasok Bahan Baku',
    'Pemasok Elektronik',
    'Pemasok Alat Kantor',
    'Lainnya'
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo ($id > 0) ? "Edit" : "Tambah"; ?> Vendor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="header">
        <h1><?php echo ($id > 0) ? "Edit Data Vendor" : "Tambah Data Vendor"; ?></h1>
        <p>Isi formulir dibawah untuk mengisi data</p>
    </div>

    <div class="isi">
        <form method="POST" class="form">

            <?php if ($error != "") { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>

            <!--Data Perusahaan-->
            <h3>1. Profil Perusahaan</h3>

            <div class="baris">
                <div class="kolom">
                    <label>Kode Vendor</label>
                    <input type="text" value="Otomatis oleh sistem" disabled>
                </div>
                <div class="kolom">
                    <label>Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" value="<?php echo htmlspecialchars($data['nama_perusahaan']); ?>" required>
                </div>
            </div>

            <div class="baris">
                <div class="kolom">
                    <label>Jenis Usaha</label>
                    <select name="kategori">
                        <?php foreach ($daftar_kategori as $kat) { ?>
                            <option value="<?php echo $kat; ?>" <?php if ($data['kategori'] == $kat) echo "selected"; ?>>
                                <?php echo $kat; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="kolom">
                    <label>Status Kemitraan</label>
                    <label>
                        <input type="checkbox" name="status" <?php if ($data['status'] == 1) echo "checked"; ?>>
                        Aktif (dapat menerima PO)
                    </label>
                </div>
            </div>

            <div class="baris">
                <div class="kolom">
                    <label>Email Perusahaan</label>
                    <input type="text" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                </div>
                <div class="kolom">
                    <label>Telepon Kantor</label>
                    <input type="text" name="telepon" value="<?php echo htmlspecialchars($data['telepon']); ?>" required>
                </div>
            </div>

            <!--Data Alamat-->
            <h3>2. Alamat Kantor</h3>

            <div class="kolom">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" rows="3" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>
            </div>
            <div class="kolom">
                <label>Kota</label>
                <input type="text" name="kota" value="<?php echo htmlspecialchars($data['kota']); ?>" required>
            </div>

            <!--Data Legalitas-->
            <h3>3. Legalitas Perusahaan</h3>

            <div class="baris">
                <div class="kolom">
                    <label>NPWP</label>
                    <input type="text" name="npwp" value="<?php echo htmlspecialchars($data['npwp']); ?>">
                </div>
                <div class="kolom">
                    <label>NIB</label>
                    <input type="text" name="nib" value="<?php echo htmlspecialchars($data['nib']); ?>">
                </div>
            </div>

            <!--Data PIC-->
            <h3>4. Kontak PIC</h3>

            <div class="baris">
                <div class="kolom">
                    <label>Nama PIC</label>
                    <input type="text" name="nama_pic" value="<?php echo htmlspecialchars($data['nama_pic']); ?>" required>
                </div>
                <div class="kolom">
                    <label>Telepon PIC</label>
                    <input type="text" name="telepon_pic" value="<?php echo htmlspecialchars($data['telepon_pic']); ?>" required>
                </div>
            </div>

            <div class="tombol-area">
                <button type="submit" class="tombol tombol-utama">Simpan Rekaman Vendor</button>
                <a href="ConnectDb.php" class="tombol tombol-abu">Batal</a>
            </div>

        </form>
    </div>

</body>
</html>