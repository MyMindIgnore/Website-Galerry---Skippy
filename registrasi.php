<?php
    include 'db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi | Web Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <header>
        <div class="container">
            <h1><a href="index.php">Skippy.</a></h1>
            <ul>
                <li><a href="index.php">Galeri</a></li>
                <li><a href="registrasi.php">Daftar</a></li>
                <li><a href="login.php">Masuk</a></li>
            </ul>
        </div>
    </header>
    
    <div class="section">
        <div class="box">
            <h3>Selamat Datang di Skippy.</h3>
            <p class="subtitle">Temukan ide baru untuk dicoba</p>
            
            <form action="" method="POST">
                <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" required>
                <input type="text" name="user" placeholder="Username" class="input-control" required>
                <input type="password" name="pass" placeholder="Password" class="input-control" required>
                <input type="email" name="email" placeholder="Email" class="input-control" required>
                
                <input type="submit" name="submit" value="Buat Akun" class="btn">
            </form>

            <?php
                if(isset($_POST['submit'])){
                    
                    $nama = ucwords($_POST['nama']);
                    $username = $_POST['user'];
                    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT); 
                    $mail = $_POST['email'];
                    
                    $insert = mysqli_query($conn, "INSERT INTO tb_admin (
                                                    admin_name, 
                                                    username, 
                                                    password, 
                                                    admin_email
                                                ) VALUES (
                                                    '".$nama."',
                                                    '".$username."',
                                                    '".$password."',
                                                    '".$mail."'
                                                )");
                                            
                    if($insert){
                        echo '<script>alert("Registrasi berhasil")</script>';
                        echo '<script>window.location="login.php"</script>';
                    }else{
                        echo '<script>alert("Gagal: '.mysqli_error($conn).'")</script>';
                    }
                }
            ?>
        </div>
    </div>
    
    <footer>
        <div class="container">
            <small>Copyright &copy; Skippy.</small>
        </div>
    </footer>
</body>
</html>