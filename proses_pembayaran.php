<?php
session_start();
include "koneksi.php";

// Periksa apakah form sudah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bukti_pembayaran'])) {
    $id_user = $_SESSION['id_user'] ?? null; // Pastikan id_user tersedia
    $id_pesanan = $_POST['id_pesanan'] ?? null; // Pastikan id_pesanan tersedia

    if (!$id_user) {
        echo "User tidak dikenali. Harap login terlebih dahulu.";
        exit;
    }

    if (!$id_pesanan) {
        echo "ID Pesanan tidak ditemukan.";
        exit;
    }

    $upload_dir = "uploads/"; // Direktori tempat menyimpan file
    $file_name = basename($_FILES['bukti_pembayaran']['name']);
    $target_file = $upload_dir . time() . "_" . $file_name; // Menambahkan timestamp untuk nama unik
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Pastikan direktori upload ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Buat direktori jika belum ada
    }

    
    // $stmt = $koneksi->prepare("
    // INSERT INTO pembayaran (id_pesanan, id_user, bukti_pembayaran, biaya_ongkir, tanggal_pembayaran)
    // VALUES (?, ?, ?, ?, NOW())
    // ");
    // $biaya_ongkir = $_SESSION['biaya_ongkir'] ?? 0; // Ambil biaya ongkir dari sesi atau set ke 0 jika tidak ada
    // $stmt->bind_param("iisd", $id_pesanan, $id_user, $target_file, $biaya_ongkir);


    // Validasi file (hanya gambar yang diperbolehkan)
    $allowed_types = ['jpg', 'jpeg', 'png'];
    if (!in_array($file_type, $allowed_types)) {
        echo "Hanya file JPG, JPEG, dan PNG yang diizinkan.";
        exit;
    }

    // Periksa ukuran file (maksimal 2MB)
    $max_file_size = 2 * 1024 * 1024; // 2MB
    if ($_FILES['bukti_pembayaran']['size'] > $max_file_size) {
        echo "Ukuran file terlalu besar. Maksimal 2MB.";
        exit;
    }

    // Periksa dan pindahkan file ke folder target
    if (move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $target_file)) {
        // Jika berhasil, simpan informasi ke database
        $stmt = $koneksi->prepare("
            INSERT INTO pembayaran (id_pesanan, id_user, bukti_pembayaran, tanggal_pembayaran)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("iis", $id_pesanan, $id_user, $target_file);

        if ($stmt->execute()) {
            echo "Bukti pembayaran berhasil diunggah!";
            // Redirect ke halaman dashboard atau konfirmasi
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Gagal menyimpan ke database: " . $stmt->error;
        }
    } else {
        echo "Terjadi kesalahan saat mengunggah file.";
    }
} else {
    echo "Form tidak valid.";
}