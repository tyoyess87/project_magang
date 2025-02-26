<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $koneksi->query("DELETE FROM keranjang WHERE id_keranjang = $id");
    header("Location: keranjang-delete.php");
}
?>