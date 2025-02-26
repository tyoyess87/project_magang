<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $koneksi->query("SELECT * FROM produk WHERE id_produk = $id");
    $data = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga_barang = $_POST['harga_barang'];
    $stok_barang = $_POST['stok_barang'];

    $koneksi->query("UPDATE produk SET nama_produk='$nama_produk', kategori='$kategori', harga_barang='$harga_barang', stok_barang='$stok_barang' WHERE id_produk=$id");
    header("Location: produk-dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-500 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Edit Produk</h1>
        <form method="post">
            <!-- Nama Produk -->
            <div class="mb-4">
                <label for="nama_produk" class="block text-gray-700 font-medium mb-2">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" value="<?= $data['nama_produk']; ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Kategori -->
            <div class="mb-4">
                <label for="kategori" class="block text-gray-700 font-medium mb-2">Kategori</label>
                <input type="text" id="kategori" name="kategori" value="<?= $data['kategori']; ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Harga Barang -->
            <div class="mb-4">
                <label for="harga_barang" class="block text-gray-700 font-medium mb-2">Harga Barang</label>
                <input type="number" id="harga_barang" name="harga_barang" value="<?= $data['harga_barang']; ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Stok Barang -->
            <div class="mb-4">
                <label for="stok_barang" class="block text-gray-700 font-medium mb-2">Stok Barang</label>
                <input type="number" id="stok_barang" name="stok_barang" value="<?= $data['stok_barang']; ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Tombol Simpan -->
            <div class="mt-6">
                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</body>

</html>
