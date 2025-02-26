<?php
    session_start();
    include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $pesan = "iki dflt";


    // Cek jika input kosong
    if (empty($username) || empty($password)) {
        $error = "Username atau password tidak boleh kosong!";
    } else {
        // Query untuk mengambil user berdasarkan username
        $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $pesan = "iki ng jro ese";

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verifikasi password
            if (password_verify($password, $row['password'])) {
                $_SESSION['id_user'] = $row['id_user'];
                $_SESSION['username'] = $username;
                $_SESSION['is_login'] = true;

                if ($row['role'] == "admin")  {
                    header("Location: dashboard.php"); // Arahkan ke dashboard atau halaman utama
                } else {
                    header("Location: index.php"); // Arahkan ke dashboard atau halaman utama
                }
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
        if (!$result) {
            die("Query error: " . $stmt->error);
        }
        
    }
}
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/dist/main.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login</title>
</head>
<body>
    <div class="flex justify-center items-center h-screen bg-indigo-600">
        <div class="w-96 p-6 shadow-lg bg-white rounded-md">
            <form action="" method="POST">
            <h1 class="text-3xl block text-center font-semibold"><i class="fa-solid fa-user"></i>Login</h1>
            <hr class="mt-3">
            <div class="mt-3">
                <label for="username" class="block text-base mb-2">Username</label>
                <input type="text" name="username" id="username" class="border w-full text-base px-2 py-1 focus:outline-none focus:ring-0 focus:border-grey-600" placeholder="Enter Username.....">
            </div>
            <div class="mt-3">
                <label for="password" class="block text-base mb-2">Password</label>
                <input type="password" name="password" id="password" class="border w-full text-base px-2 py-1 focus:outline-none focus:ring-0 focus:border-grey-600" placeholder="Enter Password.....">
            </div>
            <div class="mt-5">
                <button type="submit" class="borde-2 border-indigo-700 bg-indigo-700 text-white py-1 px-5 w-full rounded-md hover:bg-transparent hover:text-indigo-700 font-semibold">Login</button>
            </div>
            </form>
        </div>
    </div>
</body>
</html>