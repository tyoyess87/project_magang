<?php
session_start();
include "koneksi.php";

// Ambil total harga dari sesi yang sudah dihitung sebelumnya
$total = $_SESSION['total_harga'] ?? 0;

// Retrieve session data
$alamat = $_SESSION['alamat'] ?? null;
$pembayaran = $_SESSION['pembayaran'] ?? null;
$selected_products = $_SESSION['selected_products'] ?? null;

// // Calculate total price
// $total = 0;
// if ($selected_products) {
//     include "koneksi.php";
//     $placeholders = implode(',', array_fill(0, count($selected_products), '?'));
//     $stmt = $koneksi->prepare("
//         SELECT 
//             produk.nama_produk,
//             produk.harga_barang,
//             keranjang.kuantitas_barang,
//             (produk.harga_barang * keranjang.kuantitas_barang) AS total_harga
//         FROM 
//             keranjang
//         JOIN 
//             produk ON keranjang.id_produk = produk.id_produk
//         WHERE 
//             keranjang.id_keranjang IN ($placeholders)
//     ");
//     $stmt->bind_param(str_repeat('i', count($selected_products)), ...$selected_products);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     while ($row = $result->fetch_assoc()) {
//         $total += $row['total_harga'];
//     }
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg text-center max-w-lg">
        <h1 class="text-3xl font-bold text-blue-600 mb-4">Terima Kasih!</h1>
        <p class="text-lg text-gray-700">Kami sangat menghargai pembelian Anda.</p>
        <p class="text-lg text-gray-700">Pesanan Anda akan segera kami proses dan dikirim ke alamat yang Anda berikan.</p>
        <p class="text-lg text-gray-700 mt-4">Total Pembayaran:</p>
        <p class="text-2xl font-semibold text-gray-900">Rp <?= number_format($total, 0, ',', '.') ?></p>
        <p class="mt-4 text-gray-600">Jika ada pertanyaan, jangan ragu untuk menghubungi kami.</p>
        <a href="index.php" class="mt-6 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">Kembali ke Beranda</a>
    </div>
</body>

</html>
