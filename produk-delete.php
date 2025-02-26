<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $koneksi->query("DELETE FROM produk WHERE id_produk = $id");
    header("Location: produk-dashboard.php");
}
?>
