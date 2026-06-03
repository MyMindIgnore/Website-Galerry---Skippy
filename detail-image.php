<?php
    session_start();
    include 'db.php';
    if($_SESSION['status_login'] != true){
        echo '<script>window.location="login.php"</script>';
    }

    // Ambil Data Produk, User, dan Kategori
    $produk = mysqli_query($conn, "SELECT tb_image.*, tb_admin.admin_image 
                                   FROM tb_image 
                                   JOIN tb_admin ON tb_image.admin_id = tb_admin.admin_id
                                   WHERE image_id = '".$_GET['id']."' ");
                                   
    if(mysqli_num_rows($produk) == 0){
        echo '<script>window.location="dashboard.php"</script>';
    }
    $p = mysqli_fetch_object($produk);

    // Hitung Like
    $qt_like = mysqli_query($conn, "SELECT * FROM tb_like WHERE image_id = '".$_GET['id']."' ");
    $jumlah_like = mysqli_num_rows($qt_like);

    // Cek Status Like User Login
    $id_user_sekarang = $_SESSION['id'];
    $cek_status = mysqli_query($conn, "SELECT * FROM tb_like WHERE image_id = '".$_GET['id']."' AND admin_id = '$id_user_sekarang'");

    if(mysqli_num_rows($cek_status) > 0){
        $warna_love = "#ED4956";
    } else {
        $warna_love = "#262626";
    }

    // ==========================================
    // PROSES TAMBAH KOMENTAR BARU
    // ==========================================
    if(isset($_POST['submit_comment'])){
        $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);
        $image_id = $_GET['id'];
        
        if(!empty($comment_text)){
            $insert_comment = mysqli_query($conn, "INSERT INTO tb_comment (image_id, admin_id, comment_text) VALUES ('$image_id', '$id_user_sekarang', '$comment_text')");
            
            if($insert_comment){
                // Refresh halaman agar komentar langsung muncul
                echo '<script>window.location="?id='.$image_id.'"</script>';
            } else {
                echo '<script>alert("Gagal mengirim komentar!")</script>';
            }
        }
    }

    // ==========================================
    // AMBIL DATA KOMENTAR DARI DATABASE
    // ==========================================
    $komentar = mysqli_query($conn, "SELECT tb_comment.*, tb_admin.admin_name, tb_admin.admin_image 
                                     FROM tb_comment 
                                     JOIN tb_admin ON tb_comment.admin_id = tb_admin.admin_id 
                                     WHERE tb_comment.image_id = '".$_GET['id']."' 
                                     ORDER BY tb_comment.date_created ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Postingan | Skippy</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    
    <style>
        /* --- NAVBAR --- */
        header { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 15px 0; position: sticky; top: 0; z-index: 1000; transition: box-shadow 0.3s ease, padding 0.3s ease; border-bottom: 1px solid #f0f0f0; }
        header.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 10px 0; }
        header h1 a { color: #E60023; font-weight: 700; font-size: 24px; text-decoration: none; display: inline-block; transition: 0.4s; }
        header h1 a:hover { transform: scale(1.1) rotate(-3deg); }
        header ul { display: flex; gap: 5px; list-style: none; }
        header ul li a { padding: 10px 20px; border-radius: 30px; font-weight: 600; color: #555; text-decoration: none; transition: 0.3s; display: block; }
        header ul li a:hover { background: #f0f0f0; color: #111; transform: translateY(-2px); }
        .nav-logout:hover { background: #E60023 !important; color: #fff !important; box-shadow: 0 4px 10px rgba(230, 0, 35, 0.2); }

        /* --- TOMBOL KEMBALI --- */
        .btn-back { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); color: #111; text-decoration: none; font-weight: bold; font-size: 20px; margin-bottom: 20px; transition: 0.3s; border: 1px solid #eee; }
        .btn-back:hover { background: #f0f0f0; transform: translateX(-3px); }

        /* --- CONTAINER UTAMA --- */
        .detail-container-outer { padding-top: 30px; display: flex; flex-direction: column; align-items: center; min-height: 100vh; }
        .content-wrapper { width: 100%; max-width: 950px; }

        /* --- LAYOUT POSTINGAN --- */
        .insta-post-wrapper {
            display: flex; /* Kiri-Kanan */
            width: 100%;
            background-color: #fff;
            border: 1px solid #dbdbdb;
            border-radius: 4px; 
            overflow: hidden;
            height: 600px; 
        }

        /* Bagian Kiri (Gambar) */
        .post-left {
            flex: 1.5;
            background-color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid #efefef;
            position: relative; /* Agar tombol download bisa melayang di dalam div ini */
        }
        .post-left img { max-width: 100%; max-height: 100%; object-fit: contain; }

        /* --- TOMBOL DOWNLOAD --- */
        .btn-download {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            color: #111;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: 0.3s ease;
            z-index: 10;
        }
        .btn-download:hover {
            background: #fff;
            transform: translateY(-3px);
            color: #E60023;
        }

        /* Bagian Kanan (Info & Chat) */
        .post-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            height: 100%;
        }

        /* Header User (Desktop: Paling Atas Kanan) */
        .right-header {
            padding: 15px;
            border-bottom: 1px solid #efefef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
        }

        .right-body { flex: 1; overflow-y: auto; padding: 15px; }
        .right-footer { border-top: 1px solid #efefef; padding: 15px; background: #fff; }

        /* --- RESPONSIF MOBILE --- */
        @media screen and (max-width: 768px) {
            .insta-post-wrapper {
                display: flex;
                flex-direction: column; 
                height: auto; 
                border: none;
                background: transparent;
            }
            .post-right { display: contents; }
            .right-header { order: 1; border-bottom: none; padding: 10px 0; background: transparent; }
            .post-left { order: 2; width: 100%; height: auto; min-height: 300px; max-height: 500px; border-right: none; margin-bottom: 10px; border-radius: 8px; overflow: hidden; }
            .right-footer { order: 3; border-top: none; padding: 0; margin-bottom: 10px; background: transparent; }
            .right-body { order: 4; padding: 0; max-height: none; overflow: visible; }
            .content-wrapper { max-width: 100%; }
            .detail-container-outer { padding: 20px 15px; }
        }

        /* Komponen Tambahan */
        .user-info { display: flex; align-items: center; gap: 10px; }
        .small-avatar { width: 32px; height: 32px; border-radius: 50%; overflow: hidden; display: flex; justify-content: center; align-items: center; font-weight: bold; border: 1px solid #eee; }
        .small-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .username-text { font-weight: 600; font-size: 14px; color: #262626; }
        .action-icons { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .icons-left { display: flex; gap: 15px; }
        
        /* Modifikasi Comment Section agar jadi Flex Form */
        .comment-section { display: flex; align-items: center; margin-top: 10px; width: 100%; }
        .comment-section input { flex: 1; border: none; outline: none; background: transparent; padding: 8px 0; }
        
        .caption-box { display: flex; gap: 10px; margin-bottom: 15px; }
        .caption-text { font-size: 14px; word-break: break-word;}
        .time-ago { display: block; font-size: 10px; color: #8e8e8e; margin-top: 5px; text-transform: uppercase; }

        /* Modal Logout */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); z-index: 9999; justify-content: center; align-items: center; opacity: 0; transition: 0.3s; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-box { background: #fff; width: 90%; max-width: 400px; padding: 30px; border-radius: 32px; text-align: center; transform: scale(0.8); transition: 0.3s; }
        .modal-overlay.active .modal-box { transform: scale(1); }
        .btn-logout-confirm { width: 100%; padding: 12px; background: #E60023; color: #fff; border-radius: 24px; border: none; font-weight: bold; margin-bottom: 10px; cursor: pointer; }
        .btn-logout-cancel { width: 100%; padding: 12px; background: #efefef; color: #111; border-radius: 24px; border: none; font-weight: bold; cursor: pointer; }
    </style>
</head>

<body style="background-color: #fafafa;"> 
    
    <header id="mainHeader">
        <div class="container">
            <h1><a href="dashboard.php">Skippy.</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="data-image.php">Profile</a></li>
                <li><a href="#" onclick="confirmLogout(event)" class="nav-logout">Keluar</a></li>
            </ul>
        </div>
    </header>
    
    <div class="detail-container-outer">
        <div class="content-wrapper">
            
            <a href="javascript:history.back()" class="btn-back" title="Kembali">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>

            <div class="insta-post-wrapper">
                
                <div class="post-left">
                    <a href="foto/<?php echo $p->image ?>" download="<?php echo htmlspecialchars($p->image_name) ?>" class="btn-download" title="Download Gambar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </a>

                    <img src="foto/<?php echo $p->image ?>" alt="<?php echo htmlspecialchars($p->image_name) ?>">
                </div>

                <div class="post-right">
                    
                    <div class="right-header">
                        <div class="user-info">
                            <?php if($p->admin_image != null && $p->admin_image != "") { ?>
                                <div class="small-avatar">
                                    <img src="foto/<?php echo $p->admin_image ?>" alt="User Avatar">
                                </div>
                            <?php } else { ?>
                                <div class="small-avatar" style="background: #E60023; color: white;">
                                    <?php echo substr($p->admin_name, 0, 1) ?>
                                </div>
                            <?php } ?>
                            <div>
                                <span class="username-text"><?php echo $p->admin_name ?></span>
                            </div>
                        </div>
                        <div class="dot-menu">
                            <svg aria-label="More options" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24"><circle cx="12" cy="12" r="1.5"></circle><circle cx="6" cy="12" r="1.5"></circle><circle cx="18" cy="12" r="1.5"></circle></svg>
                        </div>
                    </div>

                    <div class="right-body">
                        <div class="caption-box" style="border-bottom: 1px solid #efefef; padding-bottom: 15px;">
                             <div style="display: flex; gap: 10px;">
                                 <?php if($p->admin_image != null && $p->admin_image != "") { ?>
                                    <div class="small-avatar" style="flex-shrink: 0;">
                                        <img src="foto/<?php echo $p->admin_image ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    </div>
                                <?php } else { ?>
                                    <div class="small-avatar" style="background: #E60023; color: white; flex-shrink: 0;">
                                        <?php echo substr($p->admin_name, 0, 1) ?>
                                    </div>
                                <?php } ?>
                                
                                <div>
                                    <span class="username-text"><?php echo $p->admin_name ?></span>
                                    <span class="caption-text"><?php echo $p->image_description ?></span>
                                    <br><span class="time-ago"><?php echo date('d M Y', strtotime($p->date_created)) ?></span>
                                </div>
                             </div>
                        </div>
                        
                        <?php 
                        if(mysqli_num_rows($komentar) > 0){
                            while($k = mysqli_fetch_array($komentar)){
                        ?>
                            <div class="caption-box">
                                <?php if($k['admin_image'] != null && $k['admin_image'] != "") { ?>
                                    <div class="small-avatar" style="flex-shrink: 0;">
                                        <img src="foto/<?php echo $k['admin_image'] ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    </div>
                                <?php } else { ?>
                                    <div class="small-avatar" style="background: #8e8e8e; color: white; flex-shrink: 0;">
                                        <?php echo substr($k['admin_name'], 0, 1) ?>
                                    </div>
                                <?php } ?>
                                
                                <div>
                                    <span class="username-text"><?php echo $k['admin_name'] ?></span>
                                    <span class="caption-text"><?php echo htmlspecialchars($k['comment_text']) ?></span>
                                    <br><span class="time-ago"><?php echo date('d M Y H:i', strtotime($k['date_created'])) ?></span>
                                </div>
                            </div>
                        <?php 
                            }
                        } else { 
                        ?>
                            <p style="text-align: center; color: #8e8e8e; font-size: 13px; margin-top: 20px;">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                        <?php } ?>
                    </div>

                    <div class="right-footer">
                        <div class="action-icons">
                            <div class="icons-left">
                                <a href="proses-like.php?id=<?php echo $p->image_id ?>" style="text-decoration:none;">
                                    <?php if(mysqli_num_rows($cek_status) > 0){ ?>
                                        <svg aria-label="Unlike" color="#ED4956" fill="#ED4956" height="24" role="img" viewBox="0 0 48 48" width="24"><path d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path></svg>
                                    <?php } else { ?>
                                        <svg aria-label="Like" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24"><path d="M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.072-2.652 4.959-5.197 7.222-2.512 2.243-3.865 3.469-4.303 3.752-.477-.309-2.143-1.823-4.303-3.752C5.141 14.072 2.5 12.167 2.5 9.122a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 5.827 5.015 7.97 2.873 2.498 4.654 4.403 5.745 5.313.39.325.952.325 1.342 0 1.09-.91 2.872-2.814 5.745-5.313 2.465-2.142 5.015-4.36 5.015-7.97a6.98 6.98 0 0 0-6.863-7.219Z"></path></svg>
                                    <?php } ?>
                                </a>
                                <svg aria-label="Comment" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24" style="margin-left:15px;"><path d="M20.656 17.008a9.993 9.993 0 1 0-3.59 3.615L22 22Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"></path></svg>
                                <svg aria-label="Share Post" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24" style="margin-left:15px;"><line fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2" x1="22" x2="9.218" y1="3" y2="10.083"></line><polygon fill="none" points="11.698 20.334 22 3.001 2 3.001 9.218 10.084 11.698 20.334" stroke="currentColor" stroke-linejoin="round" stroke-width="2"></polygon></svg>
                            </div>
                            <div class="icon-right">
                                <svg aria-label="Save" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24"><polygon fill="none" points="20 21 12 13.44 4 21 4 3 20 3 20 21" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polygon></svg>
                            </div>
                        </div>

                        <span class="likes-count" style="font-weight: bold; margin-top: 5px; display: block;">
                            <?php echo $jumlah_like ?> likes
                        </span>
                        
                        <form method="POST" action="" class="comment-section">
                            <svg aria-label="Emoji" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24" style="margin-right: 10px;"><path d="M15.83 10.997a1.167 1.167 0 1 0 1.167 1.167 1.167 1.167 0 0 0-1.167-1.167Zm-6.5 1.167a1.167 1.167 0 1 0-1.166 1.167 1.167 1.167 0 0 0 1.166-1.167Zm5.163 3.24a3.406 3.406 0 0 1-4.982.007 1 1 0 1 0-1.557 1.256 5.397 5.397 0 0 0 8.09 0 1 1 0 0 0-1.55-1.263ZM12 .503a11.5 11.5 0 1 0 11.5 11.5A11.513 11.513 0 0 0 12 .503Zm0 21a9.5 9.5 0 1 1 9.5-9.5 9.51 9.51 0 0 1-9.5 9.5Z"></path></svg>
                            <input type="text" name="comment_text" placeholder="Add a comment..." required autocomplete="off">
                            <button type="submit" name="submit_comment" style="background:none; border:none; color:#0095f6; font-weight:bold; cursor:pointer;">Post</button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="modal-overlay" id="logoutModal">
        <div class="modal-box">
            <div style="font-size: 50px; margin-bottom: 15px;">👋</div>
            <div class="modal-title">Ingin Keluar?</div>
            <p class="modal-desc">Anda harus masuk kembali nanti untuk mengakses profil.</p>
            <button onclick="window.location.href='Keluar.php'" class="btn-logout-confirm">Ya, Keluar</button>
            <button onclick="closeLogoutPopup()" class="btn-logout-cancel">Batal</button>
        </div>
    </div>

    <script>
        window.addEventListener('scroll', function() {
            var header = document.getElementById('mainHeader');
            if (window.scrollY > 10) header.classList.add('scrolled');
            else header.classList.remove('scrolled');
        });

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
    </script>
</body>
</html>