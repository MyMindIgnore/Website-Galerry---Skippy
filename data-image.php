<?php
    session_start();
    include 'db.php';
    if($_SESSION['status_login'] != true){
        echo '<script>window.location="login.php"</script>';
    }

    $user_id = $_SESSION['a_global']->admin_id;
    
    // Ambil Data User
    $user_query = mysqli_query($conn, "SELECT * FROM tb_admin WHERE admin_id = '$user_id'");
    $user_data = mysqli_fetch_object($user_query);
    $user_name = $user_data->admin_name;
    $user_image = $user_data->admin_image;

    // Hitung Postingan
    $count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_image WHERE admin_id = '$user_id'");
    $count_data = mysqli_fetch_assoc($count_query);
    $total_posts = $count_data['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile | Skippy</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <header id="mainHeader">
        <div class="container">
            <h1><a href="dashboard.php">Skippy.</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="data-image.php" class="active-nav">Profile</a></li>
                <li><a href="#" onclick="confirmLogout(event)" class="nav-logout">Keluar</a></li>
            </ul>
        </div>
    </header>
        
    <div class="section">
        <div class="container" style="display: block; max-width: 935px;"> 
            
            <div class="profile-header">
                <div class="profile-left">
                    <div class="profile-pic-large">
                        <?php if($user_image != null && $user_image != "") { ?>
                            <img src="foto/<?php echo $user_image ?>" alt="Profile Picture">
                        <?php } else { ?>
                            <img src="https://via.placeholder.com/150/E60023/FFFFFF?text=<?php echo substr($user_name, 0, 1) ?>" alt="Profile Picture">
                        <?php } ?>
                    </div>
                </div>

                <div class="profile-right">
                    <div class="profile-username-bar">
                        <h2 class="username"><?php echo $user_name ?></h2>
                        <div class="profile-actions">
                            <a href="profil.php" class="btn-profile">Edit Profile</a>
                        </div>
                        <svg aria-label="Options" class="settings-icon" height="24" role="img" viewBox="0 0 24 24" width="24"><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none"></circle><path d="M12 4.5a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H12.75a.75.75 0 0 1-.75-.75V4.5Zm0 14.25a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H12.75a.75.75 0 0 1-.75-.75v-.008ZM4.5 12.75a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V12.75Zm14.25 0a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75V12.75Z" fill="currentColor"></path></svg>
                    </div>

                    <div class="profile-stats">
                        <span><span class="stat-number"><?php echo $total_posts ?></span> posts</span>
                        <span><span class="stat-number">1000</span> followers</span> 
                        <span><span class="stat-number">1</span> following</span> 
                    </div>

                    <div class="profile-bio">
                        <span class="bio-name"><?php echo $user_name ?></span>
                        <a href="#" style="color: #00376b; text-decoration: none; font-weight: 600;">@<?php echo strtolower(str_replace(' ', '', $user_name)) ?></a>
                    </div>
                </div>
            </div>

            <div class="insta-grid" style="border-top: 1px solid #dbdbdb; padding-top: 20px;">
                <?php
                    $foto = mysqli_query($conn, "SELECT * FROM tb_image WHERE admin_id = '$user_id' ORDER BY image_id DESC");
                    
                    if(mysqli_num_rows($foto) > 0 ){
                        while($row = mysqli_fetch_array($foto)){
                ?>
                
                <div class="insta-card">
                    <img src="foto/<?php echo $row['image'] ?>" alt="Foto">
                    
                    <div class="insta-overlay">
                        <div class="insta-info">
                            <h4><?php echo substr($row['image_name'], 0, 15) ?></h4>
                            <div class="action-links">
                                <a href="edit-image.php?id=<?php echo $row['image_id'] ?>" class="action-btn btn-edit">Edit</a>
                                
                                <a href="#" onclick="confirmDelete(event, <?php echo $row['image_id'] ?>)" class="action-btn btn-del">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php 
                        } 
                    } else { 
                ?>
                    <p style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #888; font-size: 18px;">
                        📷 <br> Belum ada postingan.
                    </p>
                <?php } ?>
            </div>

            <div style="text-align: center; margin-top: 40px; margin-bottom: 40px;">
                <a href="tambah-image.php" class="btn btn-floating">+</a>
            </div>

        </div>
    </div>
    
    <div class="modal-overlay" id="logoutModal">
        <div class="modal-box">
            <div style="font-size: 50px; margin-bottom: 15px;">👋</div>
            <div class="modal-title">Ingin Keluar?</div>
            <p class="modal-desc">Anda harus masuk kembali nanti untuk mengakses profil.</p>
            <button onclick="window.location.href='Keluar.php'" class="btn-logout-confirm">Ya, Keluar</button>
            <button onclick="closeModal('logoutModal')" class="btn-cancel">Batal</button>
        </div>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div style="font-size: 50px; margin-bottom: 15px;">🗑️</div>
            <div class="modal-title">Anda Yakin Ingin Hapus Postingan ini?</div>
            <p class="modal-desc">Tindakan ini tidak dapat dibatalkan. Foto akan dihapus permanen.</p>
            
            <button id="btnConfirmDelete" class="btn-delete-confirm">Ya, Hapus</button>
            
            <button onclick="closeModal('deleteModal')" class="btn-cancel">Batal</button>
        </div>
    </div>

    <footer>
        <div class="container">
            <small>Copyright &copy; Skippy.</small>
        </div>
    </footer>

    <script>
        // SCROLL NAVBAR
        window.addEventListener('scroll', function() {
            var header = document.getElementById('mainHeader');
            if (window.scrollY > 10) header.classList.add('scrolled');
            else header.classList.remove('scrolled');
        });

        // FUNGSI UMUM BUKA/TUTUP MODAL
        function openModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = 'flex';
            setTimeout(function() { modal.classList.add('active'); }, 10);
        }

        function closeModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.classList.remove('active');
            setTimeout(function() { modal.style.display = 'none'; }, 300);
        }

        // LOGIKA LOGOUT
        function confirmLogout(e) {
            e.preventDefault();
            openModal('logoutModal');
        }

        // LOGIKA HAPUS FOTO
        function confirmDelete(e, id) {
            e.preventDefault();
            openModal('deleteModal');
            
            // Set link hapus pada tombol konfirmasi
            var btnDelete = document.getElementById('btnConfirmDelete');
            btnDelete.onclick = function() {
                window.location.href = 'proses-hapus.php?idp=' + id;
            };
        }

        // TUTUP MODAL JIKA KLIK LUAR
        window.onclick = function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.classList.remove('active');
                setTimeout(function() { e.target.style.display = 'none'; }, 300);
            }
        }
    </script>
</body>
</html>