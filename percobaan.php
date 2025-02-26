<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "akademik";

$koneksi    = mysqli_connect($host,$user,$pass,$db);
if(!$koneksi){
    die("tidak bisa terkoneksi ke database");
} 
$nim        = "";
$nama       = "";
$alamat     = "";
$fakultas   = "";
// msg
$sukses     = "";
$eror       = "";

if(isset($_GET['op'])){
    $op = $_GET['op'];
} else{
    $op = "";
}
if($op == 'delete'){
    $id     = $_GET['id'];
    $sql1   = "delete from mahasiswa where id='$id'";
    $q1     = mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    }else{
        $eror = "gagal melakukan delete data";
    }
}

if($op == 'edit'){
    $id         = $_GET['id'];
    $sql1       = "select * from mahasiswa where id = '$id'";
    $q1         = mysqli_query($koneksi,$sql1);
    $r1         = mysqli_fetch_array($q1);
    $nim        = $r1['nim'];
    $nama       = $r1['nama'];
    $alamat     = $r1['alamat'];
    $fakultas   = $r1['fakultas'];

    if($nim == ''){
        $eror = "Data tidak ditemukan";
    }
}

//untuk creat
if(isset($_POST['simpan'])){
    $nim            = $_POST['nim'];
    $nama           = $_POST['nama'];
    $alamat         = $_POST['alamat'];
    $fakultas       = $_POST['fakultas'];

    if($nim && $nama && $alamat && $fakultas){
        

        if($op == 'edit'){ //untuk update
            $sql1       = "update mahasiswa set nim ='$nim', nama = '$nama',alamat = '$alamat',fakultas = '$fakultas' where id = '$id'";
            $q1         = mysqli_query($koneksi, $sql1);
            if($q1){
                $sukses = "Data berhasil di update";
            }else{
                $eror   = "Data gagal diupdate";
            }
        }else{ //untuk insert
            $sql1   = "insert into mahasiswa(nim,nama,alamat,fakultas) values('$nim','$nama','$alamat','$fakultas')";
            $q1     = mysqli_query($koneksi, $sql1);
            if($q1){
                $sukses ="Berhasil Masukan Data Baru";
            }else{
                $eror   ="Gagal Masukan Data";
            }
        }
        
    } else{
        $eror = "silahkan masukan semua data";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
    .mx-auto {
        width: 800px
    }

    .card {
        margin-top: 10px;
    }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card">
            <div class="card-header">
                Creat
            </div>
            <div class="card-body">
                <?php
                if($eror){
                    ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $eror ?>
                </div>
                <?php
                    header("refresh:5;url=index.php"); // 5= detik
                }
                ?>
                <?php
                if($sukses){
                    ?>
                <div class="alert alert-succes" role="alert">
                    <?php echo $sukses ?>
                </div>
                <?php
                    header("refresh:5;url=index.php"); // 5= detik
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nim" id="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="alamat" id="alamat"
                                value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="fakultas" id="fakultas">
                                <option value="">-pilih fakultas-</option>
                                <option value="teknologi" <?php if($fakultas == "teknologi") echo "selected"?>>Teknologi
                                </option>
                                <option value="kedokteran" <?php if($fakultas == "kedoktran") echo "selected"?>>
                                    Kedokteran</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Fakultas</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                                $sql2   = "select * from mahasiswa order by id desc";
                                $q2     = mysqli_query($koneksi, $sql2);
                                $urut   = 1;
                                while($r2 = mysqli_fetch_array($q2)){
                                    $id         = $r2['id'];
                                    $nim        = $r2['nim'];
                                    $nama       = $r2['nama'];
                                    $alamat     = $r2['alamat'];
                                    $fakultas   = $r2['fakultas'];

                                    ?>
                        <tr>
                            <th scope="row"><?php echo $urut++ ?> </th>
                            <td scope="row"><?php echo $nim ?> </td>
                            <td scope="row"><?php echo $nama ?> </td>
                            <td scope="row"><?php echo $alamat ?> </td>
                            <td scope="row"><?php echo $fakultas ?> </td>
                            <td scope="row">
                                <a href="index.php?op=edit&id=<?php echo $id?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                <a href="index.php?op=delete&id=<?php echo $id?>" onclick="return confirm('yakin akan hapus data?')"><button type="button" class="btn btn-danger">Delete</button></a>
                            </td>
                        </tr>
                        <?php
                             }
                            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>