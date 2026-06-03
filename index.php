<?php
    include 'db.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Skippy | Temukan Inspirasi</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        /* CSS Banner & Kategori (Yang Lama) */
        .hero-search {
            text-align: center; padding: 50px 20px;
            background: url('https://source.unsplash.com/random/1600x900/?aesthetic') center/cover;
            background-color: #f8f8f8; margin-bottom: 20px; position: relative;
        }
        .hero-search::before {
            content: ''; position: absolute; top:0; left:0; width:100%; height:100%;
            background: rgba(255,255,255,0.85); z-index: 0;
        }
        .hero-content { position: relative; z-index: 1; max-width: 600px; margin: 0 auto; }
        .hero-search h2 { font-size: 32px; color: #333; margin-bottom: 20px; }
        .search-input {
            width: 70%; padding: 15px; border: none; border-radius: 30px 0 0 30px;
            outline: none; font-size: 16px; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .search-btn {
            width: 25%; padding: 15px; border: none; border-radius: 0 30px 30px 0;
            background-color: #E60023; color: #fff; cursor: pointer; font-weight: bold; font-size: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .category-scroll {
            display: flex; gap: 10px; overflow-x: auto; padding: 10px 0 30px 0;
            justify-content: center; flex-wrap: wrap;
        }
        .cat-pill {
            background: #fff; border: 1px solid #ddd; padding: 10px 20px;
            border-radius: 24px; font-weight: 600; color: #111; transition: 0.3s;
            white-space: nowrap; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .cat-pill:hover { background: #111; color: #fff; border-color: #111; }

        /* --- CSS BARU UNTUK MODAL POP-UP --- */
        .modal-overlay {
            display: none; /* Sembunyi default */
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Hitam transparan */
            backdrop-filter: blur(8px); /* EFEK BLUR DI BELAKANG */
            z-index: 9999;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-box {
            background: #fff;
            width: 90%;
            max-width: 400px;
            padding: 40px;
            border-radius: 32px; /* Sudut sangat bulat ala Pinterest */
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transform: scale(0.8);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .modal-overlay.active .modal-box {
            transform: scale(1); /* Animasi membesar saat muncul */
        }

        .modal-icon {
            font-size: 50px; margin-bottom: 20px; display: block;
        }

        .modal-title {
            font-size: 24px; font-weight: bold; margin-bottom: 10px; color: #111;
        }

        .modal-desc {
            font-size: 16px; color: #555; margin-bottom: 30px; line-height: 1.5;
        }

        .btn-modal-login {
            display: block; width: 100%; padding: 12px;
            background-color: #E60023; color: white;
            border-radius: 24px; font-weight: bold; text-decoration: none;
            margin-bottom: 10px; transition: 0.3s;
        }
        .btn-modal-login:hover { background-color: #ad081b; }

        .btn-modal-cancel {
            display: block; width: 100%; padding: 12px;
            background-color: #efefef; color: #111;
            border-radius: 24px; font-weight: bold; text-decoration: none; cursor: pointer;
        }
        .btn-modal-cancel:hover { background-color: #e2e2e2; }

    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1><a href="index.php">Skippy.</a></h1>
            <ul>
                <?php if(isset($_SESSION['status_login']) && $_SESSION['status_login'] == true){ ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="data-image.php">Upload</a></li>
                    <li><a href="Keluar.php">Keluar</a></li>
                <?php } else { ?>
                    <li><a href="registrasi.php">Daftar</a></li>
                    <li><a href="login.php">Masuk</a></li>
                <?php } ?>
            </ul>
        </div>
    </header>
    
    <div class="hero-search">
        <div class="hero-content">
            <h2>Temukan inspirasi Anda berikutnya</h2>
            <form action="galeri.php">
                <div style="display: flex; justify-content: center;">
                    <input type="text" name="search" class="search-input" placeholder="Mau cari apa hari ini?">
                    <input type="submit" name="cari" value="Cari" class="search-btn">
                </div>
            </form>
        </div>
    </div>
    
    <div class="section">
        <div class="container" style="display: block;">
            
            <h3 style="text-align: center; margin-bottom: 20px;">Jelajahi Ide Terbaru</h3>

            <div class="box-galeri">
                <?php
                    $foto = mysqli_query($conn, "SELECT * FROM tb_image WHERE image_status = 1 ORDER BY image_id DESC LIMIT 20");
                    
                    if(mysqli_num_rows($foto) > 0){
                        while($p = mysqli_fetch_array($foto)){
                            
                            if(isset($_SESSION['status_login']) && $_SESSION['status_login'] == true){
                                // Jika LOGIN
                                $link = "detail-image.php?id=".$p['image_id'];
                                $action = "";
                            } else {
                                // Jika BELUM LOGIN
                                $link = "#"; 
                                $action = "onclick='showLoginPopup(event)'";
                            }
                ?>
                
                <div class="col-4">
                    <a href="<?php echo $link ?>" <?php echo $action ?>>
                        <img src="foto/<?php echo $p['image'] ?>" alt="<?php echo $p['image_name'] ?>">
                    </a>
                    
                    <p class="nama"><?php echo substr($p['image_name'], 0, 30) ?></p>
                    <p class="admin"><?php echo $p['admin_name'] ?></p>
                </div>

                <?php 
                        } 
                    } else { 
                ?>
                    <p style="text-align: center;">Tidak ada foto terbaru.</p>
                <?php } ?>
            </div>

        </div>
    </div>

    <div class="modal-overlay" id="loginModal">
        <div class="modal-box">
            <span class="modal-icon">🔒</span>
            <div class="modal-title">Login Dulu Ga si</div>
            <p class="modal-desc">
                Masuk ke akun Skippy untuk melihat detail gambar ini, menyimpan ide, dan mengunduh foto.
            </p>
            <a href="login.php" class="btn-modal-login">Masuk Sekarang</a>
            <div class="btn-modal-cancel" onclick="closeLoginPopup()">Nanti Saja</div>
            <p style="margin-top: 15px; font-size: 12px; color: #777;">
                Belum punya akun? <a href="registrasi.php" style="color: #E60023; font-weight: bold;">Daftar</a>
            </p>
        </div>
    </div>
    
    <footer>
        <div class="container">
            <small>Copyright &copy; Skippy.</small>
        </div>
    </footer>

    <script>
        function showLoginPopup(e) {
            e.preventDefault(); 
            var modal = document.getElementById('loginModal');
            modal.style.display = 'flex'; 
            
            setTimeout(function() {
                modal.classList.add('active');
            }, 10);
        }

        function closeLoginPopup() {
            var modal = document.getElementById('loginModal');
            modal.classList.remove('active');
            
            setTimeout(function() {
                modal.style.display = 'none';
            }, 300);
        }

        document.getElementById('loginModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginPopup();
            }
        });
    </script>
</body>
</html>