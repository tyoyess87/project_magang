<?php
// Contoh data pesanan
$order = [
    'order_id' => 'INV123456789',
    'customer_name' => 'John Doe',
    'customer_phone' => '08123456789',
    'customer_address' => 'Jl. Raya No. 123, Jakarta',
    'order_date' => '2024-12-13',
    'items' => [
        [
            'product_name' => 'Produk A',
            'price' => 50000,
            'quantity' => 2
        ],
        [
            'product_name' => 'Produk B',
            'price' => 75000,
            'quantity' => 1
        ],
    ]
];

// Fungsi menghitung total harga
function calculateTotal($items) {
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

$total_price = calculateTotal($order['items']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-100 via-blue-300 to-blue-500 p-6 min-h-screen">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <!-- Logo dan Judul -->
        <div class="flex items-center justify-between mb-4">
            <img src="https://via.placeholder.com/100x50" alt="Logo Toko" class="h-12">
            <h1 class="text-2xl font-bold text-gray-800">Struk Belanja</h1>
        </div>

        <!-- Informasi Pesanan -->
        <div class="mb-6 border-b pb-4">
            <p><strong>ID Pesanan:</strong> <?= htmlspecialchars($order['order_id']) ?></p>
            <p><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p><strong>No HP:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
            <p><strong>Alamat:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
            <p><strong>Tanggal Pesanan:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
        </div>

        <!-- Daftar Produk -->
        <table class="table-auto w-full border-collapse border border-gray-300 mb-6">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">Produk</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">Harga</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">Jumlah</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">
                            <?= htmlspecialchars($item['product_name']) ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-right">
                            Rp <?= number_format($item['price'], 0, ',', '.') ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-right">
                            <?= htmlspecialchars($item['quantity']) ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-right">
                            Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Total Harga -->
        <div class="text-right text-lg font-bold text-gray-700">
            <p>Total: Rp <?= number_format($total_price, 0, ',', '.') ?></p>
        </div>

        <!-- Tombol Cetak -->
        <div class="mt-6 text-center">
            <button onclick="window.print()" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700">
                Cetak Struk
            </button>
        </div>
    </div>
</body>
</html>
