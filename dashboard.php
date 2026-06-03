<?php
    session_start();
    include 'db.php';
    if($_SESSION['status_login'] != true){
        echo '<script>window.location="login.php"</script>';
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Skippy</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body>
    <header id="mainHeader">
        <div class="container">
            <h1><a href="dashboard.php">Skippy.</a></h1>
            <ul>
                <li><a href="dashboard.php" class="active-nav">Dashboard</a></li>
                <li><a href="data-image.php">Profile</a></li>
                <li><a href="#" onclick="confirmLogout(event)" class="nav-logout">Keluar</a></li>
            </ul>
        </div>
    </header>
    
    <div class="section">
        <div class="container" style="display: block;"> 
            <div style="margin-bottom: 30px; text-align: center;">
                <h4 style="font-size: 24px; color: #333;">
                    Temukan Inspirasi Anda, <span style="color: #E60023;"><?php echo $_SESSION['a_global']->admin_name ?></span>
                </h4>
            </div>

            <div class="masonry-container">
                <?php
                    $query = mysqli_query($conn, "SELECT * FROM tb_image WHERE image_status = 1 ORDER BY image_id DESC");
                    
                    if(mysqli_num_rows($query) > 0){
                        while($p = mysqli_fetch_array($query)){
                ?>
                
                <div class="masonry-item">
                    <a href="detail-image.php?id=<?php echo $p['image_id'] ?>">
                        <img src="foto/<?php echo $p['image'] ?>" alt="<?php echo $p['image_name'] ?>">
                    </a>
                    
                    <div class="masonry-info">
                        <p style="font-weight: bold; font-size: 14px; margin-bottom: 2px;">
                            <?php echo substr($p['image_name'], 0, 30) ?>
                        </p>
                        <p style="font-size: 11px; opacity: 0.8;">
                            Oleh: <?php echo $p['admin_name'] ?>
                        </p>
                    </div>
                </div>

                <?php 
                        }
                    } else { 
                ?>
                    <p style="text-align: center; width: 100%; grid-column: 1/-1;">Belum ada foto yang dibagikan.</p>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <div class="modal-overlay" id="logoutModal">
        <div class="modal-box">
            <div style="font-size: 50px; margin-bottom: 15px;">👋</div>
            <div class="modal-title">Ingin Keluar?</div>
            <p class="modal-desc">
                Anda harus masuk kembali nanti untuk mengakses profil dan menyimpan ide.
            </p>
            
            <button onclick="window.location.href='Keluar.php'" class="btn-logout-confirm">Ya, Keluar</button>
            <button onclick="closeLogoutPopup()" class="btn-logout-cancel">Batal</button>
        </div>
    </div>

    <footer>
        <div class="container">
            <small>Copyright &copy; Skippy.</small>
        </div>
    </footer>

    <script>
        // Script untuk Modal Logout
        function confirmLogout(e) {
            e.preventDefault();
            var modal = document.getElementById('logoutModal');
            modal.style.display = 'flex';
            setTimeout(function() { modal.classList.add('active'); }, 10);
        }

        function closeLogoutPopup() {
            var modal = document.getElementById('logoutModal');
            modal.classList.remove('active');
            setTimeout(function() { modal.style.display = 'none'; }, 300);
        }

        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) { closeLogoutPopup(); }
        });

        // Script Baru: Efek Shadow pada Header saat Scroll
        window.addEventListener('scroll', function() {
            var header = document.getElementById('mainHeader');
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>