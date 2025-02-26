<?php
session_start();
include "koneksi.php";

// $row = [];


// jika ada method POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produk = $_POST['id_produk'];
    // cek id_roduk
    // echo "Ini dari post" . $id_produk;

       // Ambil data produk
    $query_ambil_detail = "SELECT * FROM produk WHERE id_produk = ?";
    $stmt = $koneksi->prepare($query_ambil_detail);
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // jika ada name masukkan_keranjang dengan method POST
    if (isset($_POST['masukkan_keranjang'])) {
        $id_user = $_SESSION['id_user'] ?? null;
        if (!$id_user) {
            die("Anda harus login untuk menambahkan produk ke keranjang.");
        }
    
        $id_produk = $_POST['id_produk'];
        $kuantitas_barang = intval($_POST['quantity']); // Jumlah produk ditambahkan
    
        // Cek apakah produk sudah ada di keranjang
        $cek_query = "SELECT id_keranjang, kuantitas_barang FROM keranjang WHERE id_user = ? AND id_produk = ?";
        $stmt = $koneksi->prepare($cek_query);
        $stmt->bind_param("ii", $id_user, $id_produk);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            // Produk sudah ada, update kuantitasnya
            $row = $result->fetch_assoc();
            $id_keranjang = $row['id_keranjang'];
            $kuantitas_baru = $row['kuantitas_barang'] + $kuantitas_barang;
    
            $update_query = "UPDATE keranjang SET kuantitas_barang = ?, data_diedit = NOW() WHERE id_keranjang = ?";
            $stmt_update = $koneksi->prepare($update_query);
            $stmt_update->bind_param("ii", $kuantitas_baru, $id_keranjang);
            if ($stmt_update->execute()) {
                echo "Keranjang diperbarui.";
            } else {
                echo "Gagal memperbarui keranjang.";
            }
        } else {
            // Produk belum ada, tambahkan baris baru
            $insert_query = "INSERT INTO keranjang (id_produk, id_user, kuantitas_barang, total_barang, data_dibuat, data_diedit) 
                             VALUES (?, ?, ?, ?, NOW(), NOW())";
            $stmt_insert = $koneksi->prepare($insert_query);
            $stmt_insert->bind_param("iiii", $id_produk, $id_user, $kuantitas_barang, $kuantitas_barang);
    
            if ($stmt_insert->execute()) {
                echo "Produk berhasil ditambahkan ke keranjang.";
            } else {
                echo "Gagal menambahkan ke keranjang.";
            }
        }
    
        // Tutup semua statement
        $stmt->close();
        $koneksi->close();
    
        // Redirect kembali ke halaman keranjang
        header("Location: keranjang.php");
        exit;

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- splide js -->
    <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>
    <link href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    " rel="stylesheet">
</head>

<body>

    <!-- navbar start -->

    <?php include "component/navbar_user.php" ?>

    <!-- navbar end -->
    <div class="container mx-auto p-4">
        <div class="flex flex-wrap">
            <div class="w-full md:w-1/2">
                <img src="foto/<?= $row['foto_produk'] ?>" alt="mbuh" class="rounded-lg">
            </div>

            <div class="w-full md:w-1/2 pl-4">
                <h1 class="text-2xl font-bold"><?= $row['nama_produk'] ?></h1>
                <p class="text-lg text-gray-700 mt-2">Rp <?= number_format($row['harga_barang'], 0, ',', '.') ?></p>

                <form action="" method="POST" class="mt-4">
                    <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">

                    <label for="quantity" class="block text-gray-600">Jumlah</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1"
                        class="border border-gray-300 rounded-lg px-4 py-2 w-1/3">

                    <button type="submit" name="masukkan_keranjang"
                        class="bg-blue-500 text-white px-4 py-2 mt-4 rounded-lg hover:bg-blue-600">Masukkan
                        Keranjang</button>
                </form>
            </div>
        </div>
    </div>
    <div class="border-b-2 border-black mt-4 mb-[50px]"></div>

    <div class="pl-4 mt-2">
        <H2 class="font-bold text-2xl ">Detail</H2>
        <p class="mt-2 font-semibold">Ceramic Skin Saviour Moisturizer Gel adalah pelembap revolusioner yang dirancang
            untuk memberikan hidrasi maksimal dan menutrisi kulit dengan formula ringan dan mudah meresap. Produk ini
            dirancang khusus untuk membantu menjaga kelembapan kulit, memperbaiki tekstur, dan memberikan kilau alami
            pada
            wajah.</p>
        <h2 class="font-bold text-2xl mt-4">Keunggulan Utama:</h2>
        <ul class="list-decimal ml-4">
            <li class="font-bold">Tekstur Gel yang Ringan</li>
            Formulanya berbasis gel yang lembut sehingga tidak lengket dan cepat meresap ke dalam kulit, cocok untuk
            semua
            jenis kulit, termasuk kulit berminyak dan sensitif.

            <li class="font-bold">Kandungan Ceramide</li>
            Diperkaya dengan ceramide, bahan aktif yang membantu memperkuat lapisan pelindung kulit, menjaga kelembapan,
            dan
            mencegah kekeringan.

            <li class="font-bold">Bahan Alami Pilihan</li>
            Mengandung ekstrak alami seperti aloe vera, hyaluronic acid, dan niacinamide yang membantu meredakan
            kemerahan,
            menghaluskan kulit, serta mencerahkan wajah.

            <li class="font-bold">Non-komedogenik dan Bebas Paraben</li>
            Aman digunakan setiap hari tanpa menyumbat pori-pori, serta diformulasikan tanpa bahan berbahaya sehingga
            ideal
            untuk kulit sensitif.
        </ul>

        <h2 class="font-bold text-2xl mt-4">Manfaat:</h2>
        <ul class="list-disc ml-4">
            <li>Memberikan hidrasi intensif sepanjang hari.</li>
            <li>Membantu memperbaiki skin barrier yang rusak.</li>
            <li>Membuat kulit terasa lebih lembut, halus, dan sehat bercahaya.</li>
            <li>Mengurangi tampilan garis halus dan memperbaiki tekstur kulit.</li>
        </ul>
    </div>
    </div>
    <div class="border-b-2 border-black mt-4 mb-[50px]"></div>
    <div class="text-center">
        <h2 class="font-mono font-bold text-3xl mb-[40px] ">Produk Lain</h2>
    </div>

    <!-- carausel -->
    <section class="splide" aria-label="Splide Basic HTML Example">
        <div class="splide__track">
            <ul class="splide__list ">
                <li class="splide__slide">
                    <div class="max-w-sm mx-auto bg-white border border-gray-200 rounded-lg shadow dark:bg-slate-800">
                        <a href="#">
                            <img class="rounded-t-lg" src="foto/Moisturizer_Gel_1.png" alt="" />
                        </a>
                        <div class="p-5 text-center">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-700 dark:text-blue-700">
                                    Somethinc
                                </h5>
                            </a>
                            <p class="mb-3 text-2xl font-semibold text-gray-900 dark:text-white">
                                Ceramik Skin Saviour Mointuzer Gel
                            </p>
                            <div class="">
                                <span class=" font-medium text-black dark:text-white">Rp. 52.000</span>
                            </div>
                            <a href="#"
                                class="inline-flex items-center mt-2 px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Chekout
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </li>

                <li class="splide__slide">
                    <div class="max-w-sm mx-auto bg-white border border-gray-200 rounded-lg shadow dark:bg-slate-800 ">
                        <a href="#">
                            <img class="rounded-t-lg" src="foto/Airy_Poreless_Powder_Foundation.png" alt="" />
                        </a>
                        <div class="p-5 text-center">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-700 dark:text-blue-700">
                                    Dear Me Beauty
                                </h5>
                            </a>
                            <p class="mb-3 text-2xl font-semibold text-gray-900 dark:text-white">
                                Airy Poreless Powder Foundation
                            </p>
                            <div class="">
                                <span class="text-black font-medium dark:text-white">Rp. 65.000</span>
                            </div>
                            <a href="#"
                                class="inline-flex items-center mt-2 px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Chekout
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </li>

                <li class="splide__slide">
                    <div class="max-w-sm mx-auto bg-white border border-gray-200 rounded-lg shadow dark:bg-slate-800 ">
                        <a href="#">
                            <img class="rounded-t-lg" src="foto/suncream.png" alt="" />
                        </a>
                        <div class="p-5 text-center">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-700 dark:text-blue-700">
                                    ANUA BEAUTY
                                </h5>
                            </a>
                            <p class="mb-3 text-2xl font-semibold text-gray-900 dark:text-white">
                                HEARTLEAF SILKY MOISTURE SUNCREAM
                            </p>
                            <div class="">
                                <span class="text-black font-medium dark:text-white">Rp. 72.000</span>
                            </div>
                            <a href="#"
                                class="inline-flex items-center mt-2 px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Pesan
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </li>


                <li class="splide__slide">
                    <div class="max-w-sm mx-auto bg-white border border-gray-200 rounded-lg shadow dark:bg-slate-800 ">
                        <a href="#">
                            <img class="rounded-t-lg" src="foto/Airy_Poreless_Powder_Foundation.png" alt="" />
                        </a>
                        <div class="p-5 text-center">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-700 dark:text-blue-700">
                                    Dear Me Beauty
                                </h5>
                            </a>
                            <p class="mb-3 text-2xl font-semibold text-gray-900 dark:text-white">
                                Airy Poreless Powder Foundation
                            </p>
                            <div class="">
                                <span class="text-black font-medium dark:text-white">Rp. 65.000</span>
                            </div>
                            <a href="#"
                                class="inline-flex items-center mt-2 px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Chekout
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </li>
                <li class="splide__slide">
                    <div class="max-w-sm mx-auto bg-white border border-gray-200 rounded-lg shadow dark:bg-slate-800 ">
                        <a href="#">
                            <img class="rounded-t-lg" src="foto/Airy_Poreless_Powder_Foundation.png" alt="" />
                        </a>
                        <div class="p-5 text-center">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-700 dark:text-blue-700">
                                    Dear Me Beauty
                                </h5>
                            </a>
                            <p class="mb-3 text-2xl font-semibold text-gray-900 dark:text-white">
                                Airy Poreless Powder Foundation
                            </p>
                            <div class="">
                                <span class="text-black font-medium dark:text-white">Rp. 65.000</span>
                            </div>
                            <a href="#"
                                class="inline-flex items-center mt-2 px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Chekout
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </li>
                <li class="splide__slide">
                    <div class="max-w-sm mx-auto bg-white border border-gray-200 rounded-lg shadow dark:bg-slate-800 ">
                        <a href="#">
                            <img class="rounded-t-lg" src="foto/Airy_Poreless_Powder_Foundation.png" alt="" />
                        </a>
                        <div class="p-5 text-center">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-700 dark:text-blue-700">
                                    Dear Me Beauty
                                </h5>
                            </a>
                            <p class="mb-3 text-2xl font-semibold text-gray-900 dark:text-white">
                                Airy Poreless Powder Foundation
                            </p>
                            <div class="">
                                <span class="text-black font-medium dark:text-white">Rp. 65.000</span>
                            </div>
                            <a href="#"
                                class="inline-flex items-center mt-2 px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Chekout
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </li>




            </ul>
        </div>
    </section>



    <!-- footer -->
    <?php include "component/footer.php"?>

    <!-- splide -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var splide = new Splide('.splide', {
            perPage: 3,
            perMove: 1,
        });
        splide.mount();
    });
    </script>
</body>

</html>