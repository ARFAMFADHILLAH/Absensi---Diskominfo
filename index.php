<?php
$conn = mysqli_connect("localhost", "root", "", "pt_diskominfo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SELECT * FROM t_karyawan");

// Tombol cari ditekan 
if (isset($_POST["cari"])) {
    $karyawan = cari($_POST["keyword"], $conn);
} else {
    $karyawan = [];
}

function hapus($id, $conn) {
    // Menggunakan prepared statement untuk mengamankan query
    $stmt = mysqli_prepare($conn, "DELETE FROM t_karyawan WHERE f_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    // Periksa apakah query berhasil dijalankan
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return true;
    } else {
        return false;
    }
}

// Periksa apakah ada request untuk menghapus data
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    
    // Periksa apakah ID adalah angka
    if (!is_numeric($id)) {
        echo "Error: ID harus berupa angka!";
        exit();
    }

    // Hapus data
    if (hapus($id, $conn)) {
        echo "<script>
              alert('Data berhasil dihapus!');
              window.location.href = 'index.php';
              </script>";
        exit();
    } else {
        echo "Error: Gagal menghapus data!";
    }
}

function cari($keyword, $conn) {
    $keyword = "%" . $keyword . "%";
    $stmt = mysqli_prepare($conn, "SELECT * FROM t_karyawan WHERE f_nama LIKE ?");
    mysqli_stmt_bind_param($stmt, "s", $keyword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
  <title>KOMINFOSANTIK</title>
  <style>
    body {
      background-color:whitesmoke; 
    }

    .button {
      display: inline-block;
      padding: 10px 20px;
      background-color: green; 
      color: #fff;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      cursor: pointer;
    }

    .button:hover {
      background-color: #0056b3; 
      text-decoration: none;
    }

    a {
      color: #007bff;
      text-decoration: none; 
    }

    a:hover {
      text-decoration: underline; 
    }

    .header {
      background-color: black;
      font-weight: bold;
      padding: 10px;
      text-align: center;
      color: white;
    }

    .action-links {
      text-align: center;
    }

    .action-links a {
      margin: 0 5px;
      padding: 5px 10px;
      background-color: #007bff;
      color: #fff;
      border-radius: 5px;
      text-decoration: none;
    }

    .action-links a:hover {
      background-color: #0056b3;
    }

    .search-button {
      padding: 10px 15px;
      background-color: green;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .search-button:hover {
      background-color: #218838;
    }
    
    .nrp-with-image {
      display: flex;
      align-items: center;
    }

    .nrp-with-image img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <h1>Daftar Karyawan</h1>
  <a href="tambah.php" class="button">Tambah data Karyawan</a>
  <br><br>

  <form action="" method="post">
    <input type="text" name="keyword" size="40" autofocus placeholder="Masukkan keyword pencarian.." autocomplete="off">
    <button type="submit" name="cari" class="search-button">Cari!</button>
  </form>
<br>
  <table border="1" cellpadding="10" cellspacing="0">
    <tr>
      <td class="header">id</td>
      <td class="header">Aksi</td>
      <td class="header">Nrp</td>
      <td class="header">Gambar</td>
      <td class="header">Nama</td>
      <td class="header">Email</td>
    </tr>

    <?php if (isset($_POST["cari"])) : ?>
        <?php foreach ($karyawan as $row) : ?>
            <tr>
                <td><?= ($row["f_id"]); ?></td>
                <td class="action-links">
                    <a href="ubah.php?id=<?= ($row['f_id']) ?>">Ubah</a>
                    <a href="index.php?id=<?= ($row['f_id']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                </td>
                <td><?= ($row["nrp"]); ?></td>
                <td class="nrp-with-image">
                <img src="/img/<?= ($row["f_gambar"]); ?>" alt="Profile Picture">
                </td>
                <td><?= ($row["f_nama"]); ?></td>
                <td><?= ($row["f_email"]); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= ($row["f_id"]); ?></td>
                <td class="action-links">
                    <a href="ubah.php?id=<?= ($row['f_id']) ?>">Ubah</a>
                    <a href="index.php?id=<?= ($row['f_id']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                </td>
                <td><?= ($row["nrp"]); ?></td>
                <td class="nrp-with-image">
                <img src="/img/<?= ($row["f_gambar"]); ?>" alt="Profile Picture">
                </td>
                <td><?= ($row["f_nama"]); ?></td>
                <td><?= ($row["f_email"]); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
  </table>
</body>
</html>
