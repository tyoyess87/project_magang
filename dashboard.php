<?php
session_start();
include "koneksi.php";

// Pastikan user sudah login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit;
}

// Periksa apakah koneksi ke database berhasil
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}


// Ambil jumlah keseluruhan produk
$query_total_stok = "SELECT SUM(stok_barang) as total FROM produk";
$result_total_stok = $koneksi->query($query_total_stok);
$total_stok_barang = $result_total_stok->fetch_assoc()['total'];

// Ambil jumlah jenis produk
$query_jenis_produk = "SELECT COUNT(DISTINCT kategori) as jenis FROM produk";
$result_jenis_produk = $koneksi->query($query_jenis_produk);
$jenis_produk = $result_jenis_produk->fetch_assoc()['jenis'];

// Ambil jumlah transaksi
$query_total_transaksi = "SELECT COUNT(*) as total FROM pembayaran";
$result_total_transaksi = $koneksi->query($query_total_transaksi);
$total_transaksi = $result_total_transaksi->fetch_assoc()['total'];

// Ambil jumlah user yang sudah login
$query_total_user = "SELECT COUNT(*) as total FROM user";
$result_total_user = $koneksi->query($query_total_user);
$total_user = $result_total_user->fetch_assoc()['total'];

// Cek apakah tabel `users` ada
$table_check = $koneksi->query("SHOW TABLES LIKE 'user'");
if ($table_check->num_rows == 0) {
    die("Error: Tabel 'user' tidak ditemukan di database.");
}

// Ambil data pembayaran dan user
$query = "
    SELECT 
        pembayaran.id_pembayaran,
        user.username,
        pembayaran.bukti_pembayaran,
        pembayaran.tanggal_pembayaran
    FROM 
        pembayaran
    JOIN 
        user ON pembayaran.id_user = user.id_user
";

$result = $koneksi->query($query);

// Periksa apakah query berhasil
if (!$result) {
    die("Query gagal: " . $koneksi->error);
}

// Menampilkan hasil dalam tabel jika ada data
if ($result->num_rows > 0) {
    echo "<table>
            <thead>
                <tr>
                    <th>ID Pembayaran</th>
                    <th>Username</th>
                    <th>Bukti Pembayaran</th>
                    <th>Tanggal Pembayaran</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id_pembayaran']}</td>
                <td>{$row['username']}</td>
                <td><img src='{$row['bukti_pembayaran']}' alt='Bukti' width='100'></td>
                <td>{$row['tanggal_pembayaran']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "Belum ada pembayaran.";
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- main navbar -->
        <?php include "component/navbar.php" ?>


        <!-- Main Sidebar Container -->
        <?php include "component/sidebar.php" ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Dashboard</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-4">
                            <div class="bg-blue-500 text-white p-4 rounded-lg shadow-lg">
                                <p class="text-lg font-semibold">Stok</p>
                                <h3 class="text-2xl font-bold"> <?php echo $total_stok_barang; ?> </h3>
                            </div>
                            <div class="bg-green-500 text-white p-4 rounded-lg shadow-lg">
                                <p class="text-lg font-semibold">Jenis Produk</p>
                                <h3 class="text-2xl font-bold"> <?php echo $jenis_produk; ?> </h3>
                            </div>
                            <div class="bg-yellow-500 text-white p-4 rounded-lg shadow-lg">
                                <p class="text-lg font-semibold">Total Transaksi</p>
                                <h3 class="text-2xl font-bold"> <?php echo $total_transaksi; ?> </h3>
                            </div>
                            <div class="bg-red-500 text-white p-4 rounded-lg shadow-lg">
                                <p class="text-lg font-semibold">Jumlah User</p>
                                <h3 class="text-2xl font-bold"> <?php echo $total_user; ?> </h3>
                            </div>
                        </div>
                        <!-- ./col -->
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>






        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.2.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- <script src="dist/js/pages/dashboard.js"></script> -->
</body>

</html>