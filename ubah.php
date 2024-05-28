<?php
$conn = mysqli_connect("localhost", "root", "", "pt_diskominfo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["submit"])) {
    $id = $_POST["id"];
    $nama = $_POST["f_nama"];
    $email = $_POST["f_email"];
    $nrp = $_POST["nrp"];

    // Mendapatkan data karyawan
    $query = "SELECT * FROM t_karyawan WHERE f_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "Error: Data karyawan tidak ditemukan!";
        exit();
    }

    $row = mysqli_fetch_assoc($result);
    $gambarLama = $row["f_gambar"];

    // Penanganan upload gambar
    if ($_FILES['f_gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

    // Query update data karyawan
    $query = "UPDATE t_karyawan SET f_nama=?, f_email=?, nrp=?, f_gambar=? WHERE f_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sssss', $nama, $email, $nrp, $gambar, $id);

    // Eksekusi kueri
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
        alert('Data berhasil diubah!');
        document.location.href = 'index.php';
        </script>";
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}

function upload()
{
    // Definisi fungsi upload() di sini
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <title>Ubah data Karyawan</title>
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
    <h1>Ubah Data Karyawan</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= isset($_POST["id"]) ? htmlspecialchars($_POST["id"]) : ""; ?>">

        <ul>
            <li>
                <label for="id">ID : </label>
                <input type="text" name="id" id="id" value="<?= isset($_POST["id"]) ? htmlspecialchars($_POST["id"]) : ""; ?>">
            </li>
            <br>
            <li>
                <label for="gambar">Gambar : </label>
                <input type="file" name="f_gambar" id="gambar">
            </li>
            <br>
            <li>
                <label for="nrp">NRP : </label>
                <input type="text" name="nrp" id="nrp" value="<?= isset($row["nrp"]) ? htmlspecialchars($row["nrp"]) : ""; ?>">
            </li>
            <br>
            <li>
                <label for="nama">Nama : </label>
                <input type="text" name="f_nama" id="nama" value="<?= isset($row["f_nama"]) ? htmlspecialchars($row["f_nama"]) : ""; ?>">
            </li>
            <br>
            <li>
                <label for="email">Email : </label>
                <input type="text" name="f_email" id="email" value="<?= isset($row["f_email"]) ? htmlspecialchars($row["f_email"]) : ""; ?>">
            </li>
            <br>
            <li>
                <button type="submit" name="submit">Ubah Data!</button>
            </li>
        </ul>
    </form>
</body>
</html>
