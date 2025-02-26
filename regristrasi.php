<?php
include "koneksi.php";

$popupMessage = ""; // Variable for the popup message

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gmail = $_POST['gmail'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    // Check if any field is empty
    if (empty($nama_lengkap) || empty($username) || empty($password) || empty($gmail) || empty($no_hp) || empty($alamat)) {
        $popupMessage = "Isi kolom tersebut dengan lengkap!";
    } else {
        $has_pw = password_hash($password, PASSWORD_DEFAULT);
        // Check if username already exists
        $stmt = $koneksi->prepare("SELECT id_user FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $popupMessage = "Username sudah ada!";
        } else {
            $stmt = $koneksi->prepare("INSERT INTO user(nama_lengkap, username, password, gmail, no_hp, alamat, data_dibuat, data_diedit) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("ssssss", $nama_lengkap, $username, $has_pw, $gmail, $no_hp, $alamat);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $popupMessage = "Terjadi kesalahan. Silakan coba lagi.";
            }
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
    <title>Registrasi</title>
</head>

<body>

    <!-- Form Section -->
    <div class="flex justify-center min-h-screen bg-blue-600 text-white mx-auto">
        <div class="w-[450px] m-9 p-5 bg-gray-900 flex flex-col rounded-md ">
            <form id="regForm" action="" method="post">
                <div class="flex items-center justify-center ">
                    <span class="bg-blue-400 py-2 text-3xl font-medium px-3 rounded-tl-3xl">Regi</span>
                    <span
                        class="border-y-4 border-blue-400 py-[4px] text-3xl font-medium rounded-br-3xl px-3">Ster</span>
                </div>

                <!-- Form Fields -->
                <div class="w-full border-b-2 text-lg focus:within:border-blue-400 duration-500 transform mt-16">
                    <input type="text" name="nama_lengkap" id="nama_lengkap" placeholder="Nama Lengkap"
                        class="bg-transparent w-full focus:outline-none">
                </div>
                <div class="w-full border-b-2 text-lg focus:within:border-blue-400 duration-500 transform mt-6">
                    <input type="text" name="username" id="username" placeholder="Username"
                        class="bg-transparent w-full focus:outline-none">
                </div>
                <div class="w-full border-b-2 text-lg focus:within:border-blue-400 duration-500 transform mt-6">
                    <input type="password" name="password" id="password" placeholder="Password"
                        class="bg-transparent w-full focus:outline-none">
                </div>
                <div class="w-full border-b-2 text-lg focus:within:border-blue-400 duration-500 transform mt-6">
                    <input type="email" name="gmail" id="gmail" placeholder="Gmail"
                        class="bg-transparent w-full focus:outline-none">
                </div>
                <div class="w-full border-b-2 text-lg focus:within:border-blue-400 duration-500 transform mt-6">
                    <input type="text" name="no_hp" id="no_hp" placeholder="No HP"
                        class="bg-transparent w-full focus:outline-none">
                </div>
                <div class="w-full border-b-2 text-lg focus:within:border-blue-400 duration-500 transform mt-6">
                    <input type="text" name="alamat" id="alamat" placeholder="Alamat"
                        class="bg-transparent w-full focus:outline-none">
                </div>

                <!-- Existing Link -->
                <div class="mt-6 text-center">
                    <a href="login.php"
                        class="text-center font-semibold text-gray-500 hover:text-gray-200 duration-500">Sudah Punya
                        Akun?</a>
                </div>

                <!-- Sign Up Button -->
                <div class="mt-2 text-center ">
                    <button type="button" onclick="validateForm()"
                        class="bg-blue-400 p-2 rounded-lg hover:bg-blue-200 duration-400 font-bold w-full">Sign
                        Up</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal (Pop-up) -->
    <div id="popupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4 text-center">Peringatan</h2>
            <p id="popupMessage" class="text-center text-red-500 font-medium"></p>
            <div class="mt-4 text-center">
                <button onclick="closePopup()"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Tutup</button>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Validasi dan Modal -->
    <script>
    const serverMessage = "<?php echo $popupMessage; ?>";

    // Show the popup if a message is set
    if (serverMessage) {
        showPopup(serverMessage);
    }

    function validateForm() {
        const namaLengkap = document.getElementById('nama_lengkap').value;
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const gmail = document.getElementById('gmail').value;
        const noHp = document.getElementById('no_hp').value;
        const alamat = document.getElementById('alamat').value;

        if (!namaLengkap || !username || !password || !gmail || !noHp || !alamat) {
            showPopup('Isi kolom tersebut dengan lengkap!');
        } else {
            document.getElementById('regForm').submit();
        }
    }

    function showPopup(message) {
        document.getElementById('popupMessage').innerText = message;
        document.getElementById('popupModal').classList.remove('hidden');
    }

    function closePopup() {
        document.getElementById('popupModal').classList.add('hidden');
    }
</script>

</body>

</html>
