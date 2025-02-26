<?php
session_start();
include "koneksi.php";

$id_user = $_SESSION['id_user'] ?? null;
if (!$id_user) {
    die("Error: User ID tidak ditemukan.");
}

// Mengupdate kuantitas produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $id_keranjang = intval($_POST['id_keranjang']);
    $new_quantity = max(1, intval($_POST['new_quantity'])); // Minimal kuantitas adalah 1
    $stmt = $koneksi->prepare("UPDATE keranjang SET kuantitas_barang = ? WHERE id_keranjang = ? AND id_user = ?");
    $stmt->bind_param("iii", $new_quantity, $id_keranjang, $id_user);
    $stmt->execute();

    header("Location: keranjang.php");
    exit;
}

// Menghapus item dari keranjang berdasarkan `id_keranjang`
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $koneksi->prepare("DELETE FROM keranjang WHERE id_keranjang = ? AND id_user = ?");
    $stmt->bind_param("ii", $delete_id, $id_user);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: keranjang.php");
        exit;
    } else {
        echo "<script>alert('Gagal menghapus item.');</script>";
    }
}

// Menghapus semua item dari keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear_cart'])) {
    $stmt = $koneksi->prepare("DELETE FROM keranjang WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: keranjang.php");
        exit;
    } else {
        echo "<script>alert('Gagal mengosongkan keranjang.');</script>";
    }
}




// Menampilkan item di keranjang
$stmt = $koneksi->prepare("
    SELECT 
        keranjang.id_keranjang,
        produk.nama_produk,
        produk.harga_barang,
        produk.foto_produk,
        keranjang.kuantitas_barang,
        (produk.harga_barang * keranjang.kuantitas_barang) AS total_harga
    FROM 
        keranjang
    JOIN 
        produk ON keranjang.id_produk = produk.id_produk
    WHERE 
        keranjang.id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();


// tombol checkbox
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products'])) {
    $selected_products = $_POST['selected_products'];

    // Proses produk yang dipilih untuk checkout
    foreach ($selected_products as $id_keranjang) {
        // Anda bisa mengambil data detail produk dari database berdasarkan $id_keranjang
        // dan menambahkan proses checkout di sini
    }
} 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-5">
    <!-- Header -->
    <div class="flex items-center mb-5">
        <a href="index.php" class="flex items-center">
            <img src="foto/tombol-kembali.png" alt="Kembali" class="w-[30px] h-[30px] mr-2">
            <span class="text-xl font-semibold text-gray-700">Kembali</span>
        </a>
        <img src="foto/logo_nama.png" class="ml-auto" style="width: 200px; height: 50px" alt="Logo">
    </div>

    <!-- Tabel Keranjang -->
    <div class="bg-white rounded-lg shadow-lg p-5">
        <form method="POST" id="checkoutForm" action="checkout.php">
            <table class="table-auto w-full">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Harga Satuan</th>
                        <th class="p-4 text-center">Jumlah</th>
                        <th class="p-4 text-center">Subtotal</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                    <tr class="border-b">
                        <td class="p-2 flex items-center">
                            <!-- Tambahkan Checkbox -->
                            <input type="checkbox" name="selected_products[]" value="<?= $row['id_keranjang'] ?>"
                                class="mr-3">
                            <img src="foto/<?= $row['foto_produk'] ?>" alt="<?= $row['nama_produk'] ?>"
                                class="rounded-lg w-[70px] mr-3">
                            <span class="text-gray-700"><?= $row['nama_produk'] ?></span>
                        </td>
                        <td class="p-4 text-center text-gray-700">Rp
                            <?= number_format($row['harga_barang'], 0, ',', '.') ?></td>
                        <td class="p-4 text-center text-gray-700">
                            <!-- Form Update Kuantitas -->
                            <form method="POST" class="flex items-center justify-center">
                                <input type="hidden" name="id_keranjang" value="<?= $row['id_keranjang'] ?>">
                                <button type="button" onclick="updateQuantity(this, 'decrease')"
                                    class="bg-gray-300 text-gray-700 px-2 py-1 rounded-l-lg hover:bg-gray-400">
                                    -
                                </button>
                                <input type="text" name="new_quantity" value="<?= $row['kuantitas_barang'] ?>"
                                    class="w-12 text-center border-t border-b border-gray-300 focus:outline-none"
                                    readonly>
                                <button type="button" onclick="updateQuantity(this, 'increase')"
                                    class="bg-gray-300 text-gray-700 px-2 py-1 rounded-r-lg hover:bg-gray-400">
                                    +
                                </button>
                            </form>
                        </td>

                        <td class="p-4 text-center text-gray-700">Rp
                            <span id="subtotal_<?= $row['id_keranjang'] ?>" data-harga="<?= $row['harga_barang'] ?>">
                                <?= number_format($row['total_harga'], 0, ',', '.') ?>
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <a href="keranjang.php?delete_id=<?= $row['id_keranjang'] ?>"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')"
                                class="bg-red-500 text-white py-1 px-3 rounded-lg hover:bg-red-600">
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php }
                    } else { ?>
                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-500">Keranjang kosong.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
    </div>

    <!-- Tombol Aksi -->
    <div class="mt-5 flex justify-between items-center">
        <form method="POST">
            <button type="submit" name="clear_cart" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                Kosongkan Keranjang
            </button>
        </form>
        <div class="flex space-x-3">
            <a href="index.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                Belanja Lagi
            </a>
            <!-- Tombol Pesan Sekarang -->
            <button type="submit" form="checkoutForm"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Pesan Sekarang
            </button>
        </div>
    </div>



    <script>
    function updateQuantity(button, action) {
        // Ambil elemen input di dalam form yang sama
        const form = button.closest('form');
        const input = form.querySelector('input[name="new_quantity"]');
        const idKeranjang = form.querySelector('input[name="id_keranjang"]').value;

        // Hitung nilai baru
        let quantity = parseInt(input.value);
        quantity = isNaN(quantity) ? 1 : quantity;
        if (action === 'decrease' && quantity > 1) {
            quantity--;
        } else if (action === 'increase') {
            quantity++;
        }

        // Perbarui nilai input
        input.value = quantity;

        // Update subtotal
        const subtotalElement = document.getElementById(`subtotal_${idKeranjang}`);
        const hargaSatuan = parseInt(subtotalElement.getAttribute('data-harga'));
        const subtotal = quantity * hargaSatuan;

        // Format subtotal dengan pemisah ribuan
        const formattedSubtotal = new Intl.NumberFormat('id-ID').format(subtotal);
        subtotalElement.textContent = formattedSubtotal;

        // Kirimkan permintaan POST secara otomatis untuk memperbarui kuantitas
        fetch('keranjang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                id_keranjang: idKeranjang,
                new_quantity: quantity,
                update_quantity: 1
            })
        }).then(response => {
            if (response.ok) {
                // Berhasil diperbarui, Anda dapat menambahkan notifikasi jika perlu
                console.log('Kuantitas diperbarui');
            } else {
                console.error('Gagal memperbarui kuantitas');
            }
        }).catch(error => {
            console.error('Kesalahan:', error);
        });
    }
    </script>

</body>


</html>