<?php
session_start();
include 'db.php';

// Cek login
if($_SESSION['status_login'] != true){
    echo '<script>window.location="login.php"</script>';
}

$image_id = $_GET['id'];
$admin_id = $_SESSION['id']; // Mengambil ID user yang sedang login

// Cek apakah user ini sudah pernah like foto ini?
$cek = mysqli_query($conn, "SELECT * FROM tb_like WHERE image_id = '$image_id' AND admin_id = '$admin_id'");

if(mysqli_num_rows($cek) > 0){
    // Jika SUDAH ada, berarti user ingin UNLIKE (Hapus data)
    $query = mysqli_query($conn, "DELETE FROM tb_like WHERE image_id = '$image_id' AND admin_id = '$admin_id'");
} else {
    // Jika BELUM ada, berarti user ingin LIKE (Insert data)
    $query = mysqli_query($conn, "INSERT INTO tb_like VALUES (null, '$image_id', '$admin_id', CURRENT_TIMESTAMP)");
}

// Kembalikan user ke halaman detail foto
echo '<script>window.location="detail-image.php?id='.$image_id.'"</script>';
?>