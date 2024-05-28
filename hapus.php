<?php
require 'index.php';

if(isset($_GET["f_id"])) {
    $id = $_GET["f_id"];
    // Periksa apakah ID adalah angka
    if (!is_numeric($id)) {
        echo "Error: ID harus berupa angka!";
        exit();
    }

    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = mysqli_prepare($conn, "DELETE FROM t_karyawan WHERE f_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    // Periksa apakah data berhasil dihapus
    if (mysqli_affected_rows($conn) > 0) {
        echo "<script>
        alert('Data berhasil dihapus!');
        document.location.href = 'index.php';
        </script>";
        // Redirect setelah menghapus data
        exit();
        // Pastikan tidak ada kode yang dieksekusi setelah ini
    } else {
        echo "Error: Data tidak ditemukan atau gagal dihapus!";
    }
} else {
    echo "Error: ID tidak ditemukan!";
}
?>
