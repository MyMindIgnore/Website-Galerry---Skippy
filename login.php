<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Skippy</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    
    <header>
        <div class="container">
            <h1><a href="index.php">Skippy.</a></h1>
            <ul>
                <li><a href="registrasi.php">Daftar</a></li>
                <li><a href="login.php" style="background-color: #E60023; color: #fff;">Masuk</a></li>
            </ul>
        </div>
    </header>

    <div class="section">
        <div class="box">
            <h3 style="margin-bottom: 20px;">Login</h3>
            
            <form action="" method="POST">
                <input type="text" name="user" placeholder="Username" class="input-control" required>
                <input type="password" name="pass" placeholder="Password" class="input-control" required>
                <input type="submit" name="submit" value="Masuk" class="btn">
            </form>

            <div style="margin-top: 15px; font-size: 14px; text-align: center;">
                Belum punya akun? <a href="registrasi.php" style="color:#E60023; font-weight:bold;">Daftar</a>
            </div>

            <?php
                if(isset($_POST['submit'])){
                    session_start();
                    include 'db.php';
                    $user = mysqli_real_escape_string($conn, $_POST['user']);
                    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

                    $cek = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username = '".$user."'");
                    if(mysqli_num_rows($cek) > 0){
                        $d = mysqli_fetch_object($cek);
                        if(password_verify($pass, $d->password)){
                            $_SESSION['status_login'] = true;
                            $_SESSION['a_global'] = $d;
                            $_SESSION['id'] = $d->admin_id;
                            echo '<script>window.location="dashboard.php"</script>';
                        }else{
                            echo '<script>alert("Password Salah")</script>';
                        }
                    }else{
                        echo '<script>alert("Username Salah")</script>';
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