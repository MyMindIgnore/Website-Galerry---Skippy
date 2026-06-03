<?php
    session_start();
    include 'db.php';
    
    // 1. Cek Login
    if($_SESSION['status_login'] != true){
        echo '<script>window.location="login.php"</script>';
        exit(); 
    }

    // Saat login, menyimpan ID user di session
    $id_user_login = $_SESSION['id']; 

    // 2. Amankan ID dari URL 
    $image_id_url = mysqli_real_escape_string($conn, $_GET['id']);

    // 3. Query dengan validasi kempemilikan dari si user
    $produk = mysqli_query($conn, "SELECT * FROM tb_image WHERE image_id = '$image_id_url' AND admin_id = '$id_user_login'");
    
    // Jika tidak ada data, berarti ID salah / gambar itu bukan punya user ini
    if(mysqli_num_rows($produk) == 0){
        echo '<script>window.location="data-image.php"</script>';
        exit();
    }
    
    $p = mysqli_fetch_object($produk);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Foto | Skippy</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    
    <style>
        .edit-container {
            display: flex;
            justify-content: center;
            padding: 40px 20px;
        }

        .edit-card {
            display: flex;
            background-color: #fff;
            width: 100%;
            max-width: 900px;
            border-radius: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            flex-direction: row;
        }

        /* KOLOM KIRI: PREVIEW GAMBAR */
        .edit-left {
            flex: 1;
            background-color: #f2f2f2;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .preview-img {
            max-width: 100%;
            max-height: 500px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            object-fit: contain;
            margin-bottom: 20px;
        }

        /* KOLOM KANAN: FORMULIR */
        .edit-right {
            flex: 1.2; 
            padding: 40px;
            display: flex;
            flex-direction: column;
        }

        .edit-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #111;
        }

        /* Styling Input */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }

        .edit-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 16px;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
            font-family: inherit;
        }

        .edit-input:focus {
            border-color: #0074e8;
            box-shadow: 0 0 0 4px rgba(0,116,232, 0.1);
        }

        .edit-textarea {
            width: 100%;
            height: 120px;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 16px;
            font-size: 16px;
            outline: none;
            resize: none;
            font-family: inherit;
        }

        /* Styling File Input */
        .file-upload-btn {
            background: #e2e2e2;
            padding: 10px 20px;
            border-radius: 24px;
            font-weight: bold;
            cursor: pointer;
            display: inline-block;
            transition: 0.3s;
        }
        .file-upload-btn:hover { background: #d1d1d1; }

        /* Tombol Save & Cancel */
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            justify-content: flex-end;
        }

        .btn-save {
            background-color: #E60023;
            color: white;
            padding: 12px 24px;
            border-radius: 24px;
            border: none;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-save:hover { background-color: #ad081b; }

        .btn-cancel {
            background-color: #efefef;
            color: #111;
            padding: 12px 24px;
            border-radius: 24px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }
        .btn-cancel:hover { background-color: #e2e2e2; }

        /* Info Tags */
        .info-tags {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .tag {
            background: #eee;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 12px;
            color: #555;
            font-weight: 600;
        }

        /* Responsif HP */
        @media screen and (max-width: 768px) {
            .edit-card { flex-direction: column; }
            .edit-left { padding: 40px 20px 20px 20px; }
            .edit-right { padding: 20px; }
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
                <li><a href="Keluar.php">Keluar</a></li>
            </ul>
        </div>
    </header>
    
    <div class="section">
        <div class="container edit-container">
            
            <div class="edit-card">
                <form action="" method="POST" enctype="multipart/form-data" style="display: flex; width: 100%; flex-wrap: wrap;">
                    
                    <div class="edit-left">
                        <img src="foto/<?php echo $p->image ?>" class="preview-img" id="img-preview" />
                        
                        <div style="position: relative; overflow: hidden; display: inline-block;">
                            <label for="upload-file" class="file-upload-btn">Ganti Gambar</label>
                            <input type="file" name="gambar" id="upload-file" style="position: absolute; left: 0; top: 0; opacity: 0; cursor: pointer; height: 100%; width: 100%;" onchange="previewImage(event)">
                        </div>
                    </div>

                    <div class="edit-right">
                        <h3 class="edit-title">Edit Pin ini</h3>
                        
                        <input type="hidden" name="kategori" value="<?php echo $p->category_name ?>">
                        <input type="hidden" name="namauser" value="<?php echo $p->admin_name ?>">
                        <input type="hidden" name="foto" value="<?php echo $p->image ?>">

                        <div class="form-group">
                            <label class="form-label">Judul</label>
                            <input type="text" name="nama" class="edit-input" placeholder="Tambahkan judul" value="<?php echo $p->image_name ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="edit-textarea" name="deskripsi" placeholder="Ceritakan tentang Pin ini..."><?php echo $p->image_description ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status Postingan</label>
                            <select class="edit-input" name="status">
                                <option value="1" <?php echo ($p->image_status == 1)? 'selected':''; ?>>Aktif</option>
                                <option value="0" <?php echo ($p->image_status == 0)? 'selected':''; ?>>Arsip</option> 
                            </select>
                        </div>

                        <div class="btn-group">
                            <a href="data-image.php" class="btn-cancel">Batal</a>
                            <input type="submit" name="submit" value="Simpan" class="btn-save">
                        </div>

                    </div>
                </form>
            </div>
            <?php
                if(isset($_POST['submit'])){
                    
                    // Data Input
                    $kategori   = $_POST['kategori'];
                    $user       = $_POST['namauser'];
                    $nama       = $_POST['nama'];
                    $deskripsi  = $_POST['deskripsi'];
                    $status     = $_POST['status'];
                    $foto       = $_POST['foto']; // Nama foto lama
                    
                    // Data Gambar Baru
                    $filename   = $_FILES['gambar']['name'];
                    $tmp_name   = $_FILES['gambar']['tmp_name'];
                        
                    // Logika Ganti Gambar
                    if($filename != ''){
                        $type1 = explode('.', $filename);
                        $type2 = strtolower(end($type1)); // Ambil ekstensi terakhir & lowercase

                        $newname = 'foto'.time().'.'.$type2;
                        $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                    
                        if(!in_array($type2, $tipe_diizinkan)){
                            echo '<script>alert("Format file tidak diizinkan (hanya jpg, jpeg, png, gif)")</script>';
                            return false;
                        }else{
                            // Hapus foto lama jika ada filenya
                            if(file_exists('./foto/'.$foto)){
                                unlink('./foto/'.$foto);
                            }
                            move_uploaded_file($tmp_name, './foto/'.$newname);
                            $namagambar = $newname;  
                        }
                    }else{
                        // Jika tidak ganti gambar, pakai nama lama
                        $namagambar = $foto;
                    }
                    
                    // Query Update
                        $update = mysqli_query($conn, "UPDATE tb_image SET
                            category_name       = '".$kategori."',
                            admin_name          = '".$user."',
                            image_name          = '".$nama."',
                            image_description   = '".$deskripsi."',
                            image               = '".$namagambar."',
                            image_status        = '".$status."'
                            WHERE image_id      = '".$p->image_id."' 
                            AND admin_id        = '".$id_user_login."' "); 

                    if($update){
                        echo '<script>alert("Berhasil menyimpan perubahan")</script>';
                        echo '<script>window.location="data-image.php"</script>';
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

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('img-preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>