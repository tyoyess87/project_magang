<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga_barang = $_POST['harga_barang'];
    $stok_barang = $_POST['stok_barang'];

    // Validasi data
    if (empty($nama_produk) || empty($kategori) || $harga_barang <= 0 || $stok_barang < 0) {
        echo "<script>alert('Semua field harus diisi dengan benar!');</script>";
    } else {
        // Lakukan penyimpanan ke database
        $stmt = $koneksi->prepare("INSERT INTO produk (nama_produk, kategori, harga_barang, stok_barang) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $nama_produk, $kategori, $harga_barang, $stok_barang);
        $stmt->execute();

        // Redirect ke dashboard setelah berhasil
        header("Location: produk-dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Tambah Produk</title>
</head>

<body class="bg-blue-500 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-sm bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-4">Tambah Produk Baru</h1>
        <form method="post" action="">
            <!-- Nama Produk -->
            <div class="mb-4">
                <label for="nama_produk" class="block text-gray-700 font-medium mb-2">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Kategori -->
            <div class="mb-4">
                <label for="kategori" class="block text-gray-700 font-medium mb-2">Kategori</label>
                <input type="text" id="kategori" name="kategori" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Harga Barang -->
            <div class="mb-4">
                <label for="harga_barang" class="block text-gray-700 font-medium mb-2">Harga Barang</label>
                <input type="number" id="harga_barang" name="harga_barang" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Stok Barang -->
            <div class="mb-4">
                <label for="stok_barang" class="block text-gray-700 font-medium mb-2">Stok Barang</label>
                <input type="number" id="stok_barang" name="stok_barang" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Tombol -->
            <div class="flex justify-between">
                <button type="submit"
                    class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition">
                    Tambah Produk
                </button>
                <a href="produk-dashboard.php"
                    class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</body>

</html>
