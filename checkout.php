<?php
session_start();
include "koneksi.php";
$id_user = $_SESSION['id_user'] ?? null;




// Menampilkan item di checkout
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
        keranjang.id_user = ?
");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();


// Menampilkan gambar di checkout

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products'])) {
    $selected_products = $_POST['selected_products'];
    $placeholders = implode(',', array_fill(0, count($selected_products), '?'));

    // Query untuk menampilkan gambar hanya untuk produk yang dipilih
    $stmt_gambar = $koneksi->prepare("
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
            keranjang.id_keranjang IN ($placeholders)
    ");

    // Bind parameter untuk query
    $stmt_gambar->bind_param(str_repeat('i', count($selected_products)), ...$selected_products);
    $stmt_gambar->execute();
    $result_gambar = $stmt_gambar->get_result();
    

    $total = 0;


    // Ambil data produk yang dipilih dari halaman sebelumnya
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products'])) {
        $selected_products = $_POST['selected_products'];

        // Query untuk menampilkan data produk yang dipilih
        $placeholders = implode(',', array_fill(0, count($selected_products), '?'));
        $stmt = $koneksi->prepare("
        SELECT 
            keranjang.id_keranjang,
            produk.nama_produk,
            produk.harga_barang,
            keranjang.kuantitas_barang,
            (produk.harga_barang * keranjang.kuantitas_barang) AS total_harga
        FROM 
            keranjang
        JOIN 
            produk ON keranjang.id_produk = produk.id_produk
        WHERE 
            keranjang.id_keranjang IN ($placeholders)
    ");
        $stmt->bind_param(str_repeat('i', count($selected_products)), ...$selected_products);
        $stmt->execute();
        $result = $stmt->get_result();

        $total = 0;
    } else {
        echo "Tidak ada produk yang dipilih.";
        exit;
    }


    // Ambil id_pesanan terbaru dari database
    $id_pesanan = null; // Set default null

    $query_pesanan = $koneksi->prepare("SELECT id_pesanan FROM pesanan WHERE id_user = ? ORDER BY id_pesanan DESC LIMIT 1");
    $query_pesanan->bind_param("i", $id_user);
    $query_pesanan->execute();
    $result_pesanan = $query_pesanan->get_result();
    if ($row_pesanan = $result_pesanan->fetch_assoc()) {
        $id_pesanan = $row_pesanan['id_pesanan'];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products'])) {
        $selected_products = $_POST['selected_products'];
        // Proses produk yang dipilih
        foreach ($selected_products as $id_keranjang) {
            // Ambil detail produk berdasarkan $id_keranjang
        }
    } else {
        echo "Tidak ada produk yang dipilih.";
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-white-900 ">


    <!-- // Ambil data produk yang dipilih dari halaman sebelumnya
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products'])) {
        $selected_products = $_POST['selected_products'];

        // Query untuk menampilkan data produk yang dipilih
        $placeholders = implode(',', array_fill(0, count($selected_products), '?'));
        $stmt = $koneksi->prepare("
        SELECT 
            keranjang.id_keranjang,
            produk.nama_produk,
            produk.harga_barang,
            keranjang.kuantitas_barang,
            (produk.harga_barang * keranjang.kuantitas_barang) AS total_harga
        FROM 
            keranjang
        JOIN 
            produk ON keranjang.id_produk = produk.id_produk
        WHERE 
            keranjang.id_keranjang IN ($placeholders)
    ");
        $stmt->bind_param(str_repeat('i', count($selected_products)), ...$selected_products);
        $stmt->execute();
        $result = $stmt->get_result();

        $total = 0;
    } else {
        echo "Tidak ada produk yang dipilih.";
        exit;
    } -->

        <div class="container mx-auto   p-6">
            <div class="flex px-2">
                <a href="keranjang.php" class="flex">
                    <img src="foto/tombol-kembali.png" alt="" class="w-[30px] h-[30px]">
                    <h1 class="text-2xl font-bold text-gray-800 mb-5 ml-5">CHECKOUT</h1>
                </a>
            </div>

            <!-- tombol modal -->
            <div class="bg-white p-5 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Alamat Pengiriman</h2>
                <div id="alamatPengiriman" class="text-gray-700">
                    <p>Klik tombol di bawah untuk mengisi alamat pengiriman.</p>
                </div>
                <button id="openModalBtn" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Isi Alamat
                </button>
            </div>
        </div>

        <!-- Modal Alamat -->
        <div id="modal"
            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden overflow-auto">
            <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/2 p-6 relative py-[150px] mt-[200px]">
                <!-- Tombol Tutup -->
                <button id="closeModalBtn" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">&times;</button>

                <!-- Konten Modal -->
                <h2 class="text-xl font-bold mb-4 text-center">Form Alamat</h2>
                <form id="formAlamat" class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" id="nama" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                        <input type="text" id="no_hp" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                        <input type="text" id="provinsi" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="kota" class="block text-sm font-medium text-gray-700">Kota</label>
                        <input type="text" id="kota" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                        <input type="text" id="kecamatan" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="desa" class="block text-sm font-medium text-gray-700">Desa</label>
                        <input type="text" id="desa" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                        <input type="text" id="kode_pos" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea id="alamat_lengkap" rows="3"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-lg"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="button" id="simpanAlamatBtn"
                            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- tombol modal pembayaran -->
        <div class="container mx-auto   p-6">
            <div class="bg-white p-5 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">METODE PEMBAYARAN</h2>
                <div id="pembayaran" class="text-gray-700">
                    <p>Isi Metode Pembayaran</p>
                </div>
                <button id="openpembayaranBtn" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Klik Disini
                </button>
            </div>
        </div>

        <!-- Modal Pembayaran -->
        <div id="modalpembayaran"
            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden overflow-auto">
            <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/2 p-6 relative py-[150px] mt-[250px]">
                <!-- Tombol Tutup -->
                <button id="closepembayaranBtn"
                    class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">&times;</button>


                <!-- Konten Modal -->
                <h2 class="text-xl font-bold mb-4 text-center">Scan Kode Tersebut</h2>
                <form id="formpembayaran" method="POST" action="proses_pembayaran.php" enctype="multipart/form-data"
                    class="space-y-4">
                    <input type="text" name="id_pesanan" value="<?= htmlspecialchars($id_pesanan) ?>">
                    <div>
                        <div class="text-center">
                            <img src="foto/kode_qr1.jpg" class="px-[70px]" alt="">
                        </div>
                    </div>
                    <div>
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700">Kirimkan Bukti
                            Transfer</label>
                        <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-lg">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <div class="border-b-2 border-black"></div>


        <!-- menampilkan gambar -->
        <div class="bg-white p-2 rounded-lg shadow-md">
            <?php
            if ($result_gambar->num_rows > 0) {
                while ($row = $result_gambar->fetch_assoc()) { ?>
                    <div class="w-full md:w-1/2 flex">
                        <img src="foto/<?= $row['foto_produk'] ?>" alt="Produk" class="rounded-lg w-[120px]">
                        <h3 class="font-bold text-2xl py-[40px]">
                            <?= $row["nama_produk"] ?>
                        </h3>
                    </div>
                    <?php
                    $total += $row['total_harga'];
                }
            } else {
                echo "<p class='text-gray-700'>Tidak ada produk yang dipilih.</p>";
            }
} else {
    echo "<p class='text-gray-700'>Tidak ada produk yang dipilih.</p>";
} ?>
    </div>
    <!-- end menampilkan -->

    <div class="border-b-2 border-black"></div>





    <!-- Tabel Keranjang -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <table class="mt-5 table-auto w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="p-4 text-left">Nama Produk</th>
                    <th class="p-4 text-left">Harga</th>
                    <th class="p-4 text-left">Jumlah</th>
                    <th class="p-4 text-left">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        ?>
                        <tr>
                            <td class="px-5 py-2 item-center"><?= $row["nama_produk"] ?></td>
                            <td class="px-5 py-2 item-center"><?= $row["harga_barang"] ?></td>
                            <td class="px-5 py-2 item-center"><?= $row["kuantitas_barang"] ?></td>
                            <td class="px-5 py-2 item-center"><?= $row["total_harga"] ?></td>
                        </tr>
                        <?php $total += $row['total_harga'];
                    }
                } ?>
            </tbody>
            <tfoot class="bg-gray-200">
                <tr>
                    <td colspan="3" class="p-4 font-bold text-right">Total Keseluruhan</td>
                    <td class="p-4 font-bold">Rp <?= number_format($total, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>


    <!-- Tombol Pesan -->
    <form method="POST" action="nota_pesanan.php" class="mt-5 mb-[30px]">
        <button type="submit" name="checkout" class="bg-blue-500 text-white w-full py-2 rounded-lg hover:bg-sky-600">
            Chekout
        </button>
    </form>




    <!-- js modal alamat -->
    <script>
        // Ambil elemen
        const modal = document.getElementById('modal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const simpanAlamatBtn = document.getElementById('simpanAlamatBtn');
        const alamatPengiriman = document.getElementById('alamatPengiriman');

        // Tampilkan modal
        openModalBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        // Tutup modal
        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Simpan data alamat ke halaman checkout
        simpanAlamatBtn.addEventListener('click', () => {
            const nama = document.getElementById('nama').value;
            const noHp = document.getElementById('no_hp').value;
            const provinsi = document.getElementById('provinsi').value;
            const kota = document.getElementById('kota').value;
            const kecamatan = document.getElementById('kecamatan').value;
            const desa = document.getElementById('desa').value;
            const kodePos = document.getElementById('kode_pos').value;
            const alamatLengkap = document.getElementById('alamat_lengkap').value;

            // Gabungkan data
            const alamatHtml = `
                <p><strong>Nama Lengkap:</strong> ${nama}</p>
                <p><strong>Nomor HP:</strong> ${noHp}</p>
                <p><strong>Provinsi, Kota, Kecamatan, Desa:</strong> ${provinsi}, ${kota}, ${kecamatan}, ${desa}</p>
                <p><strong>Kode Pos:</strong> ${kodePos}</p>
                <p><strong>Alamat Lengkap:</strong> ${alamatLengkap}</p>
            `;

            // Tampilkan di halaman checkout
            alamatPengiriman.innerHTML = alamatHtml;

            // Tutup modal
            modal.classList.add('hidden');
        });

        // Tutup modal jika klik di luar modal
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>






    <!-- js modal pembayaran -->
    <script>
        // Ambil elemen
        const modalpembayaran = document.getElementById('modalpembayaran');
        const openpembayaranBtn = document.getElementById('openpembayaranBtn');
        const closepembayaranBtn = document.getElementById('closepembayaranBtn');
        const simpanpembayaranBtn = document.getElementById('simpanpembayaranBtn');
        const pembayaran = document.getElementById('pembayaran');

        // Tampilkan modal
        openpembayaranBtn.addEventListener('click', () => {
            modalpembayaran.classList.remove('hidden');
        });

        // Tutup modal
        closepembayaranBtn.addEventListener('click', () => {
            modalpembayaran.classList.add('hidden');
        });

        // Simpan data alamat ke halaman checkout
        simpanpembayaranBtn.addEventListener('click', () => {
            const nama = document.getElementById('transfer').value;

            // Gabungkan data
            const alamatHtml = `
                <p><strong>Bukti Pembayaran:</strong> ${transfer}</p>
            `;

            // Tampilkan di halaman checkout
            pembayaran.innerHTML = alamatHtml;

            // Tutup modal
            modalpembayaran.classList.add('hidden');
        });

        // Tutup modal jika klik di luar modal
        window.addEventListener('click', (e) => {
            if (e.target === modalpembayaran) {
                modalpembayaran.classList.add('hidden');
            }
        });
    </script>

</body>

</html>