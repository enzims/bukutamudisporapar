<?php 
include "koneksi.php";
include "header.php"; 
?>

<?php
// Uji tombol simpan
if (isset($_POST['bsimpan'])) {
    // Mendapatkan tanggal saat ini dengan format YYYY-MM-DD
    $tgl = date('Y-m-d');
    
    // Mengambil dan memfilter input dari form untuk keamanan
    $nama = htmlspecialchars($_POST['nama'], ENT_QUOTES);
    $alamat = htmlspecialchars($_POST['alamat'], ENT_QUOTES);
    $tujuan = htmlspecialchars($_POST['tujuan'], ENT_QUOTES);
    $nope = htmlspecialchars($_POST['nope'], ENT_QUOTES);
    
    // Pengaturan file foto
    $rand = rand();
    $ekstensi = array('png', 'jpg', 'jpeg', 'gif');
    $filename = $_FILES['foto']['name'];
    $ukuran = $_FILES['foto']['size'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $path = 'gambar/' . $rand . '_' . $filename;

    if (!in_array($ext, $ekstensi)) {
        header("location:admin.php?alert=gagal_ekstensi");
    } else {
        if ($ukuran < 1044070) {
            $xx = $rand . '_' . $filename;
            move_uploaded_file($_FILES['foto']['tmp_name'], $path);
            mysqli_query($koneksi, "INSERT INTO tamu (tanggal, nama, alamat, tujuan, nope, user_foto) VALUES ('$tgl', '$nama', '$alamat', '$tujuan', '$nope', '$path')");
            header("location:admin.php?alert=berhasil");
        } else {
            header("location:admin.php?alert=gagal_ukuran");
        }
    }
}
?>

<!--head-->
<div class="head text-center">
    <img src="assets/img/logo2.png" width="200">
    <h2 class="text-white">Sistem Informasi Buku Tamu <br> DISPORAPAR Kota Samarinda </h2>
</div>

<!-- Form Pengunjung -->
<div class="row mt-2">
    <div class="col-sm-7 mb-3">
        <div class="card shadow bg-gradient light">
            <div class="cardbody">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Identitas Pengunjung</h1>
                    </div>
                    <form class="user" method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="nama" placeholder="Nama" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="alamat" placeholder="Instansi">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="tujuan" placeholder="Keperluan" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="nope" placeholder="No.Hp" required>
                        </div>
                        <div class="form-group">
                            <label>Foto:</label>
                            <input type="file" name="foto" required="required">
                            <p style="color: red">Silahkan Memasukan Foto</p>
                        </div>
                        <button type="submit" name="bsimpan" class="btn btn-primary btn-user btn-block">Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Pengunjung -->
    <div class="col-sm-5 mb-3">
        <div class="card shadow">
            <div class="card-body">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Statistik Pengunjung</h1>
                </div>
                
                <?php
                // Query Statistik
                $tgl_sekarang = date('Y-m-d');
                $kemaren = date('Y-m-d', strtotime('-1 day', strtotime($tgl_sekarang)));
                $seminggu = date('Y-m-d', strtotime('-1 week +1 day', strtotime($tgl_sekarang)));
                $bulan_ini = date('m');

                // Query statistik berdasarkan periode waktu
                $query_hari_ini = mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE tanggal = '$tgl_sekarang'");
                $query_kemaren = mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE tanggal = '$kemaren'");
                $query_minggu_ini = mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE tanggal BETWEEN '$seminggu' AND '$tgl_sekarang'");
                $query_bulan_ini = mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE MONTH(tanggal) = '$bulan_ini'");
                $query_total = mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu");

                $tgl_sekarang = mysqli_fetch_array($query_hari_ini);
                $kemaren = mysqli_fetch_array($query_kemaren);
                $seminggu = mysqli_fetch_array($query_minggu_ini);
                $sebulan = mysqli_fetch_array($query_bulan_ini);
                $keseluruhan = mysqli_fetch_array($query_total);
                ?>

                <table class="table table-bordered">
                    <tr>
                        <td>Hari Ini</td>
                        <td>: <?= $tgl_sekarang[0] ?></td>
                    </tr>
                    <tr>
                        <td>Kemarin</td>
                        <td>: <?= $kemaren[0] ?></td>
                    </tr>
                    <tr>
                        <td>Minggu Ini</td>
                        <td>: <?= $seminggu[0] ?></td>
                    </tr>
                    <tr>
                        <td>Bulan Ini</td>
                        <td>: <?= $sebulan[0] ?></td>
                    </tr>
                    <tr>
                        <td>Total Keseluruhan</td>
                        <td>: <?= $keseluruhan[0] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Data Pengunjung Hari Ini -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Pengunjung Hari Ini [<?= date('Y-m-d') ?>]</h6>
    </div>
    <div class="card-body">
        <a href="rekapitulasi.php" class="btn btn-success mb-3"><i class="fa fa-table"></i> Rekapitulasi Pengunjung</a>
        <a href="logout.php" class="btn btn-secondary mb-3"><i class="fa fa-sign-out-alt"></i> Logout</a>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Instansi</th>
                        <th>Keperluan</th>
                        <th>No Hp</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        $tgl = date('Y-m-d');
                        $tampil = mysqli_query($koneksi, "SELECT * FROM tamu WHERE tanggal = '$tgl' ORDER BY id DESC");
                        $no = 1;
                        while ($data = mysqli_fetch_array($tampil)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['nama'] ?></td>
                            <td><?= $data['tanggal'] ?></td>
                            <td><?= $data['alamat'] ?></td>
                            <td><?= $data['tujuan'] ?></td>
                            <td><?= $data['nope'] ?></td>
                            <td>
                                <img src="<?= $data['user_foto'] ?>" width="100" height="100">
                            </td>
                        </tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
