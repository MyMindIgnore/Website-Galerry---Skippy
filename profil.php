<?php
    session_start();
    include 'db.php';
    if($_SESSION['status_login'] != true){
        echo '<script>window.location="login.php"</script>';
    }

    // Ambil Data User Terbaru
    $query = mysqli_query($conn, "SELECT * FROM tb_admin WHERE admin_id ='".$_SESSION['id']."'");
    $d = mysqli_fetch_object($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profil | Skippy</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        /* CSS Header & Nav  */
        header { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); padding: 15px 0; position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid #f0f0f0; }
        .container { display: flex; justify-content: space-between; align-items: center; }
        header h1 a { color: #E60023; font-weight: 700; font-size: 24px; text-decoration: none; }
        header ul { display: flex; gap: 5px; list-style: none; }
        header ul li a { padding: 10px 20px; border-radius: 30px; font-weight: 600; color: #555; text-decoration: none; transition: 0.3s; }
        header ul li a:hover { background: #f0f0f0; color: #111; }
        .nav-logout:hover { background: #E60023 !important; color: #fff !important; }

        /* CSS Settings Layout */
        .settings-container { display: flex; max-width: 900px; margin: 0 auto; gap: 40px; padding-top: 20px; }
        .settings-sidebar { flex: 1; min-width: 200px; }
        .settings-menu a { display: block; padding: 10px 15px; border-radius: 8px; font-weight: 600; color: #111; margin-bottom: 5px; text-decoration: none; }
        .settings-menu a:hover { background: #efefef; }
        .settings-menu a.active { border-left: 3px solid #111; background: #f9f9f9; }
        
        .settings-content { flex: 3; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 12px; color: #555; margin-bottom: 5px; font-weight: bold; }
        .input-profile { width: 100%; padding: 12px 16px; border: 2px solid #ddd; border-radius: 16px; font-size: 16px; outline: none; transition: 0.3s; }
        .input-profile:focus { border-color: #0074e8; }
        .btn-save { background: #E60023; color: #fff; padding: 12px 24px; border-radius: 24px; border: none; font-weight: bold; cursor: pointer; font-size: 16px; transition: 0.3s; }
        .btn-save:hover { background: #ad081b; }

        /* CSS KHUSUS FOTO PROFIL */
        .profile-upload-area {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .avatar-preview {
            width: 100px; height: 100px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #efefef;
            display: flex; justify-content: center; align-items: center;
            font-size: 35px; font-weight: bold; color: #555;
            position: relative;
            border: 2px solid #fff;
            box-shadow: 0 0 0 1px #ddd;
        }

        .avatar-preview img {
            width: 100%; height: 100%; object-fit: cover;
        }

        /* Input File Tersembunyi */
        #upload-foto { display: none; }

        .btn-change-photo {
            background-color: #efefef;
            color: #111;
            padding: 10px 20px;
            border-radius: 24px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: inline-block;
        }
        .btn-change-photo:hover { background-color: #e2e2e2; }

        @media screen and (max-width: 768px) {
            .settings-container { flex-direction: column; }
            .settings-sidebar { display: none; }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1><a href="dashboard.php">Skippy.</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="data-image.php">Profile</a></li>
                <li><a href="Keluar.php" class="nav-logout">Keluar</a></li>
            </ul>
        </div>
    </header>
    
    <div class="section">
        <div class="container settings-container">
            <div class="settings-sidebar">
                <div class="settings-menu">
                    <a href="#" class="active">Edit Profil</a>
                    <a href="data-image.php">Lihat Profil Saya</a>
                </div>
            </div>

            <div class="settings-content">
                <h2 style="font-size: 28px; font-weight: 600; margin-bottom: 10px;">Edit Profil</h2>
                <p style="color: #555; margin-bottom: 30px;">Sesuaikan foto profil dan informasi pribadi Anda.</p>

                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <div class="profile-upload-area">
                        <div class="avatar-preview">
                            <?php if($d->admin_image != null && $d->admin_image != "") { ?>
                                <img src="foto/<?php echo $d->admin_image ?>" id="img-preview">
                            <?php } else { ?>
                                <span id="text-preview"><?php echo substr($d->admin_name, 0, 1) ?></span>
                                <img src="" id="img-preview" style="display: none;">
                            <?php } ?>
                        </div>
                        <div>
                            <label for="upload-foto" class="btn-change-photo">Ubah Foto</label>
                            <input type="file" name="foto" id="upload-foto" accept="image/*" onchange="previewProfile(event)">
                            <p style="font-size: 12px; color: #777; margin-top: 8px;">Disarankan rasio 1:1 (Kotak)</p>
                        </div>
                    </div>

                    <input type="hidden" name="foto_lama" value="<?php echo $d->admin_image ?>">

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="input-profile" value="<?php echo $d->admin_name ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="user" class="input-profile" value="<?php echo $d->username ?>" required>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <input type="submit" name="submit_profil" value="Simpan Perubahan" class="btn-save">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
        if(isset($_POST['submit_profil'])){
            $nama   = ucwords($_POST['nama']);
            $user   = $_POST['user'];
            $email  = $_POST['email'];
            $foto_lama = $_POST['foto_lama'];

            // Cek apakah user ganti gambar
            $filename = $_FILES['foto']['name'];
            $tmp_name = $_FILES['foto']['tmp_name'];

            if($filename != '') {
                // User Upload Foto Baru
                $type1 = explode('.', $filename);
                $type2 = strtolower(end($type1));
                $newname = 'profile_'.time().'.'.$type2;
                $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'webp');

                if(!in_array($type2, $tipe_diizinkan)){
                    echo '<script>alert("Format foto tidak diizinkan")</script>';
                    return false;
                } else {
                    // Hapus foto lama jika ada
                    if($foto_lama != '' && file_exists('./foto/'.$foto_lama)){
                        unlink('./foto/'.$foto_lama);
                    }
                    move_uploaded_file($tmp_name, './foto/'.$newname);
                    $namagambar = $newname;
                }
            } else {
                // User tidak ganti gambar
                $namagambar = $foto_lama;
            }

            // Query Update Database (Tambah admin_image)
            $update = mysqli_query($conn, "UPDATE tb_admin SET 
                                    admin_name = '".$nama."',
                                    username = '".$user."',
                                    admin_email = '".$email."',
                                    admin_image = '".$namagambar."'
                                    WHERE admin_id = '".$d->admin_id."'");

            if($update){
                // Update Session Nama agar header langsung berubah
                $_SESSION['a_global']->admin_name = $nama;
                
                echo '<script>alert("Profil berhasil diperbarui!")</script>';
                echo '<script>window.location="profil.php"</script>';
            }else{
                echo '<script>alert("Gagal: '.mysqli_error($conn).'")</script>';
            }
        }
    ?>

    <script>
        // Script Preview Gambar Bulat
        function previewProfile(event) {
            var input = event.target;
            var previewImg = document.getElementById('img-preview');
            var previewText = document.getElementById('text-preview');
            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    if(previewText) previewText.style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>