<?php
   // 1. Sambungin dulu ke database
   include 'db.php';
      
   // 2. Cek apakah ada ID gambar yang dikirim buat dihapus?
   if(isset($_GET['idp'])){
       
       // 3. Cari nama file fotonya di database berdasarkan ID tadi
       $foto = mysqli_query($conn, "SELECT image FROM tb_image WHERE image_id = '".$_GET['idp']."' ");
       $p = mysqli_fetch_object($foto);
       
       // 4. Hapus file fisik fotonya dari folder penyimpanan (biar gak ngebekas di server)
       unlink('./foto/'.$p->image);
       
       // 5. Setelah filenya hilang, sekarang hapus datanya dari database
      $delete = mysqli_query($conn, "DELETE FROM tb_image WHERE image_id = '".$_GET['idp']."' ");
      
      // 6. Kalau sudah beres semua, balikin user ke halaman galeri
      echo '<script>window.location="data-image.php"</script>';
   }

?>