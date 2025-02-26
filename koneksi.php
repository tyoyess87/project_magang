<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "olshop_skincare";

$koneksi = mysqli_connect($host, $user, $password, $dbname);

if(!$koneksi ){
    echo "errrorrrr";
}


?>