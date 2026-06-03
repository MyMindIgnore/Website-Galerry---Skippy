<?php
    session_start();
    include 'db.php';
    if($_SESSION['status_login'] != true){
        echo '<script>window.location="login.php"</script>';
    }
    
    $status_upload = ''; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Postingan | Skippy</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    
    <style>
        .upload-card {
            background-color: #fff;
            border-radius: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            overflow: hidden;
            min-height: 500px;
        }
        .upload-left { flex: 1.2; padding: 40px; border-right: 1px solid #f0f0f0; display: flex; flex-direction: column; }
        .upload-right { flex: 0.8; background-color: #f9f9f9; padding: 40px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .page-title { font-size: 24px; font-weight: bold; margin-bottom: 30px; color: #111; }
        .form-group { margin-bottom: 20px; }
        .form-label { font-size: 12px; font-weight: bold; color: #555; margin-bottom: 8px; display: block; }
        .input-control { width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 16px; font-size: 16px; outline: none; transition: 0.3s; }
        .input-control:focus { border-color: #0074e8; background-color: #fff; }
        .textarea-control { width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 16px; font-size: 16px; outline: none; resize: none; height: 120px; font-family: inherit; }
        .preview-container { width: 100%; height: 100%; max-height: 450px; background-color: #e9e9e9; border-radius: 24px; display: flex; justify-content: center; align-items: center; overflow: hidden; position: relative; cursor: pointer; border: 2px dashed #ccc; transition: 0.3s; }
        .preview-container:hover { background-color: #e0e0e0; border-color: #999; }
        .preview-placeholder { text-align: center; color: #777; padding: 20px; }
        .preview-img { width: 100%; height: 100%; object-fit: cover; display: none; }
        .btn-change-img { margin-top: 15px; background: #fff; border: 1px solid #ccc; padding: 8px 15px; border-radius: 20px; font-size: 14px; font-weight: bold; cursor: pointer; display: none; }
        .btn-submit { background-color: #E60023; color: white; padding: 15px; border: none; border-radius: 30px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 20px; transition: 0.3s; }
        .btn-submit:hover { background-color: #ad081b; }
        @media screen and (max-width: 768px) { .upload-card { flex-direction: column-reverse; } .upload-right { height: 300px; padding: 20px; } }

        /* --- CSS MODAL SUKSES BARU --- */
        .success-overlay {
            display: none;
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            z-index: 9999;
            justify-content: center; align-items: center;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .success-overlay.active { display: flex; opacity: 1; }

        .success-box {
            background: #fff; width: 90%; max-width: 380px; padding: 40px;
            border-radius: 32px; text-align: center;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            transform: scale(0.7); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .success-overlay.active .success-box { transform: scale(1); }

        /* Animasi Centang */
        .check-circle {
            width: 80px; height: 80px; background: #00C853;
            border-radius: 50%; display: flex; justify-content: center; align-items: center;
            margin: 0 auto 20px; color: white; font-size: 40px;
            box-shadow: 0 4px 15px rgba(0, 200, 83, 0.3);
        }
        
        .success-title { font-size: 24px; font-weight: bold; color: #111; margin-bottom: 10px; }
        .success-desc { font-size: 16px; color: #555; margin-bottom: 30px; }
        
        .btn-success-primary {
            display: block; width: 100%; padding: 14px;
            background-color: #111; color: white;
            border-radius: 24px; font-weight: bold; text-decoration: none;
            margin-bottom: 10px; transition: 0.3s; border: none; cursor: pointer;
        }
        .btn-success-primary:hover { background-color: #333; }

        .btn-success-secondary {
            display: block; width: 100%; padding: 14px;
            background-color: #f0f0f0; color: #111;
            border-radius: 24px; font-weight: bold; text-decoration: none;
            transition: 0.3s; border: none; cursor: pointer;
        }
        .btn-success-secondary:hover { background-color: #e0e0e0; }

    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1><a href="dashboard.php">Skippy.</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="data-image.php">Profile</a></li>
                <li><a href="Keluar.php">Keluar</a></li>
            </ul>
        </div>
    </header>
    
    <div class="section">
        <div class="container">
            
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="upload-card">
                    
                    <div class="upload-left">
                        <h3 class="page-title">Buat Postingan Baru</h3>

                        <input type="hidden" name="adminid" value="<?php echo $_SESSION['a_global']->admin_id ?>">
                        <input type="hidden" name="namaadmin" value="<?php echo $_SESSION['a_global']->admin_name ?>">

                        <div class="form-group">
                            <label class="form-label">Kategori</label>
                            <?php 
                                $result = mysqli_query($conn,"select * from tb_category"); 
                                $jsArray = "var prdName = new Array();\n"; 
                            ?>
                            <select class="input-control" name="kategori" onchange="document.getElementById('prd_name').value = prdName[this.value]" required>
                                <option value="">Pilih topik</option>
                                <?php 
                                    while ($row = mysqli_fetch_array($result)) { 
                                        echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>'; 
                                        $jsArray .= "prdName['" . $row['category_id'] . "'] = '" . addslashes($row['category_name']) . "';\n";
                                    }
                                ?>
                            </select>
                            <input type="hidden" name="nama_kategori" id="prd_name">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Judul</label>
                            <input type="text" name="nama" class="input-control" placeholder="Tambahkan judul" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="textarea-control" name="deskripsi" placeholder="Ceritakan tentang pin ini..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="input-control" name="status">
                                <option value="1">Aktif</option>
                                <option value="0">Arsip</option> 
                            </select>
                        </div>

                        <input type="submit" name="submit" value="Terbitkan" class="btn-submit">
                    </div>

                    <div class="upload-right">
                        <input type="file" name="gambar" id="file-input" style="display: none;" onchange="previewImage(event)" required>
                        <div class="preview-container" onclick="document.getElementById('file-input').click()">
                            <div class="preview-placeholder" id="placeholder-content">
                                <div style="font-size: 40px; margin-bottom: 10px;">⬆️</div>
                                <div>Klik untuk upload gambar</div>
                                <div style="font-size: 12px; margin-top: 5px; opacity: 0.6;">JPG kurang dari 10MB</div>
                            </div>
                            <img id="img-preview" class="preview-img" src="#" alt="Preview">
                        </div>
                        <button type="button" class="btn-change-img" id="change-btn" onclick="document.getElementById('file-input').click()">Ganti Gambar</button>
                    </div>

                </div>
            </form>

            <?php
                if(isset($_POST['submit'])){
                    
                    $kategori   = $_POST['kategori'];
                    $nama_ka    = $_POST['nama_kategori'];
                    $ida        = $_POST['adminid'];
                    $user       = $_POST['namaadmin'];
                    $nama       = $_POST['nama'];
                    $deskripsi  = $_POST['deskripsi'];
                    $status     = $_POST['status'];
                    
                    $filename = $_FILES['gambar']['name'];
                    $tmp_name = $_FILES['gambar']['tmp_name'];
                    
                    if($filename != ''){
                        $type1 = explode('.', $filename);
                        $type2 = strtolower(end($type1));
                        $newname = 'foto'.time().'.'.$type2; 
                        $tipe_gambar = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                        $ukuran_file = $_FILES['gambar']['size']; 
                        $batas_ukuran = 10485760; 

                        if(!in_array($type2, $tipe_gambar)){
                            // Cek Format
                            echo '<script>alert("Format file tidak diizinkan")</script>';
                        } elseif($ukuran_file > $batas_ukuran) {
                            // Cek Ukuran 
                            echo '<script>alert("Ukuran file terlalu besar! Maksimal 10MB.")</script>';
                        } else {
                            // Jika lolos kedua cek di atas, baru upload
                            move_uploaded_file($tmp_name, './foto/'.$newname);
                            
                            $insert = mysqli_query($conn, "INSERT INTO tb_image VALUES (
                                        null,
                                        '".$kategori."',
                                        '".$nama_ka."',
                                        '".$ida."',
                                        '".$user."',
                                        '".$nama."',
                                        '".$deskripsi."',
                                        '".$newname."',
                                        '".$status."',
                                        null ) ");
                                            
                            if($insert){
                                $status_upload = 'success';
                            }else{
                                echo 'gagal'.mysqli_error($conn);
                            }
                        }
                    }
                }
            ?>
        </div>
    </div>
    
    <div class="success-overlay" id="successModal">
        <div class="success-box">
            <div class="check-circle">✓</div>
            
            <div class="success-title">Berhasil!</div>
            <p class="success-desc">Postingan Anda telah diterbitkan dan sekarang dapat dilihat di profil Anda.</p>
            
            <a href="data-image.php" class="btn-success-primary">Lihat Profil Saya</a>
            <button onclick="window.location.href='tambah-image.php'" class="btn-success-secondary">Buat Postingan Lain</button>
        </div>
    </div>
    
    <footer>
        <div class="container">
            <small>Copyright &copy; Skippy.</small>
        </div>
    </footer>
    
    <script>
        function previewImage(event) {
            var input = event.target;
            var preview = document.getElementById('img-preview');
            var placeholder = document.getElementById('placeholder-content');
            var changeBtn = document.getElementById('change-btn');
            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                    changeBtn.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // FUNGSI UNTUK MEMUNCULKAN POPUP JIKA BERHASIL MENGUPLOAD FILE
        <?php if($status_upload == 'success') { ?>
            document.addEventListener("DOMContentLoaded", function() {
                var modal = document.getElementById('successModal');
                modal.style.display = 'flex';
                setTimeout(function() {
                    modal.classList.add('active');
                }, 50);
            });
        <?php } ?>
    </script>
    <script type="text/javascript"><?php echo $jsArray; ?></script>
</body>
</html>