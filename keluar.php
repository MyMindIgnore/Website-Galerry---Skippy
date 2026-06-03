<?php
    // 1. Panggil dulu sesi yang lagi aktif 
    session_start();

    // ini proses inti Logout-nya
    session_destroy();

    // 3. Kalau sudah bersih, kembali ke halaman login
    echo '<script>window.location="login.php"</script>';
?>