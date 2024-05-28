<?php
// koneksi ke DBMS
$conn = mysqli_connect("localhost", "root", "", "pt_diskominfo");

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {

    // ambil data dari tiap elemen dalam form
    $id = $_POST["f_id"];
    $nama = $_POST["f_nama"];
    $email = $_POST["f_email"];
    $nrp = $_POST["nrp"];

    //upload gambar
    $gambar = upload();
    if (!$gambar) {
        // jika upload gagal
        echo "<script>alert('Upload gambar gagal!');</script>";
    } else {
        // query insert data karyawan
        $query = "INSERT INTO t_karyawan (f_id, f_nama, f_email, nrp, f_gambar) VALUES ('$id','$nama','$email', '$nrp', '$gambar')";

        // eksekusi kueri
        if (mysqli_query($conn, $query)) {
            echo "<script>
            alert('data berhasil ditambahkan');
            document.location.href = 'index.php';
            </script>";
            // Redirect setelah menambahkan data
            exit();
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    }
}

function upload()
{
    $namafile = $_FILES['f_gambar']['name'];
    $ukuranfile = $_FILES['f_gambar']['size'];
    $error = $_FILES['f_gambar']['error'];
    $tmpName = $_FILES['f_gambar']['tmp_name'];

    // cek apakah tidak ada gambar yang diupload
    if ($error === 4) {
        echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
        return false;
    }

    // cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namafile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('Yang anda upload bukan gambar!');</script>";
        return false;
    }

    // cek jika ukurannya terlalu besar
    if ($ukuranfile > 1000000) {
        echo "<script>alert('Ukuran gambar terlalu besar!');</script>";
        return false;
    }

    // lolos pengecekan, gambar siap diupload
    // generate nama gambar baru agar tidak ada yang sama
    $namafilebaru = uniqid();
    $namafilebaru .= '.';
    $namafilebaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namafilebaru);
    return $namafilebaru;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <title>Tambah data Karyawan</title>
    <style>
        button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff; 
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Tambah Data Karyawan</h1>

    <form action="" method="post" enctype="multipart/form-data">

    <ul>
        <li>
            <label for="f_id">ID : </label>
            <input type="text" name="f_id" id="id">
        </li>
        <br>
        <li>
            <label for="f_gambar">Gambar : </label>
            <input type="file" name="f_gambar" id="gambar">
        </li>
        <br>
        <li>
            <label for="nrp">NRP : </label>
            <input type="text" name="nrp" id="nrp">
        </li>
        <br>
        <li>
            <label for="f_nama">Nama : </label>
            <input type="text" name="f_nama" id="nama">
        </li>
        <br>
        <li>
            <label for="f_email">Email : </label>
            <input type="text" name="f_email" id="email">
        </li>
        <br>
        <li>
            <button type="submit" name="submit">Tambah Data!</button>
        </li>
    </ul>
    </form>
    
</body>
</html>
