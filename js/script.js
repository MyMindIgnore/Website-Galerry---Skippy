/* 1. GLOBAL & HEADER (Digunakan di Dashboard, Profile, Detail, Edit) */

// A. Efek Scroll Navbar 
window.addEventListener('scroll', function() {
    var header = document.getElementById('mainHeader');
    // Cek apakah header ada di halaman ini 
    if (header) {
        if (window.scrollY > 10) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
});

// Modal Logout
function confirmLogout(e) {
    if(e) e.preventDefault(); 
    var modal = document.getElementById('logoutModal');
    if(modal) {
        modal.style.display = 'flex';
        setTimeout(function() { modal.classList.add('active'); }, 10);
    }
}

function closeLogoutPopup() {
    var modal = document.getElementById('logoutModal');
    if(modal) {
        modal.classList.remove('active');
        setTimeout(function() { modal.style.display = 'none'; }, 300);
    }
}

document.addEventListener("DOMContentLoaded", function() {
    var logoutModal = document.getElementById('logoutModal');
    if (logoutModal) {
        logoutModal.addEventListener('click', function(e) {
            if (e.target === this) { closeLogoutPopup(); }
        });
    }
});

/* 2. HALAMAN PROFILE (data-image.php) */

// Fungsi Membuka Modal Hapus dan Set Link ID
function confirmDelete(e, id) {
    if(e) e.preventDefault();
    
    var modal = document.getElementById('deleteModal');
    var btnConfirm = document.getElementById('btnConfirmDelete');
    
    if(modal && btnConfirm) {
        modal.style.display = 'flex';
        setTimeout(function() { modal.classList.add('active'); }, 10);
        
        // Update aksi tombol konfirmasi agar menghapus ID yang benar
        btnConfirm.onclick = function() {
            window.location.href = 'proses-hapus.php?idp=' + id;
        };
    }
}

// Fungsi Menutup Modal (Bisa dipakai untuk modal hapus dll)
function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    if(modal) {
        modal.classList.remove('active');
        setTimeout(function() { modal.style.display = 'none'; }, 300);
    }
}

// Event Listener Global untuk menutup modal apapun jika klik di luar (Overlay)
window.onclick = function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('active');
        setTimeout(function() { e.target.style.display = 'none'; }, 300);
    }
}


/* 3. HALAMAN UPLOAD & EDIT (tambah-image.php, edit-image.php, profil.php) */

function previewImage(event) {
    var input = event.target;
    var preview = document.getElementById('img-preview');
    var placeholder = document.getElementById('placeholder-content'); // Khusus tambah-image
    var changeBtn = document.getElementById('change-btn'); // Khusus tambah-image
    var textPreview = document.getElementById('text-preview'); // Khusus profil.php
    
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            if(preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            // Sembunyikan elemen placeholder jika ada 
            if(placeholder) placeholder.style.display = 'none';
            // Tampilkan tombol ganti jika ada
            if(changeBtn) changeBtn.style.display = 'block';
            // Sembunyikan inisial huruf jika ada 
            if(textPreview) textPreview.style.display = 'none';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function previewProfile(event) {
    previewImage(event);
}

/* 4. index.php */
function showLoginPopup(e) {
    if(e) e.preventDefault();
    var modal = document.getElementById('loginModal');
    if(modal) {
        modal.style.display = 'flex';
        setTimeout(function() { modal.classList.add('active'); }, 10);
    }
}

function closeLoginPopup() {
    var modal = document.getElementById('loginModal');
    if(modal) {
        modal.classList.remove('active');
        setTimeout(function() { modal.style.display = 'none'; }, 300);
    }
}

document.addEventListener("DOMContentLoaded", function() {
    var loginModal = document.getElementById('loginModal');
    if (loginModal) {
        loginModal.addEventListener('click', function(e) {
            if (e.target === this) { closeLoginPopup(); }
        });
    }
});