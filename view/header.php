<?php
// Memulai session jika session PHP belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menentukan apakah skrip berjalan di dalam direktori view/ atau di root project
$php_self     = str_replace('\\', '/', $_SERVER['PHP_SELF'] ?? '');
$current_page = basename($php_self);
$is_in_view   = (strpos($php_self, '/view/') !== false) || (strpos($php_self, 'view/') === 0);

// Menyesuaikan path relatif asset berdasarkan lokasi file pemanggil
$base_path    = $is_in_view ? '../' : './';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php // Menampilkan judul halaman secara dinamis ?>
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>Sistem Informasi UMKM Saparua</title>
    <?php // Memuat file CSS utama ?>
    <link rel="stylesheet" href="<?= $base_path ?>style.css">
</head>
<body>

    <?php // Komponen navigasi bar atas ?>
    <header class="navbar">
        <div class="nav-container">
            <?php // Logo dan nama brand aplikasi ?>
            <a href="<?= $base_path ?>index.php" class="brand">
                <span>Info UMKM Saparua</span>
            </a>
            <?php // Daftar link menu navigasi dengan penanda menu aktif ?>
            <ul class="nav-links">
                <li>
                    <a href="<?= $base_path ?>index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= $base_path ?>view/kategori.php" class="<?= $current_page === 'kategori.php' ? 'active' : '' ?>">
                        Data Kategori
                    </a>
                </li>
                <li>
                    <a href="<?= $base_path ?>view/umkm.php" class="<?= $current_page === 'umkm.php' ? 'active' : '' ?>">
                        Data UMKM
                    </a>
                </li>
                <li>
                    <a href="<?= $base_path ?>view/menu.php" class="<?= $current_page === 'menu.php' ? 'active' : '' ?>">
                        Data Menu
                    </a>
                </li>
            </ul>
        </div>
    </header>

    <?php // Pembuka kontainer konten utama ?>
    <main class="main-content">

        <?php // Menampilkan notifikasi pesan kilat (flash message) dari session jika ada ?>
        <?php if (isset($_SESSION['flash'])): ?>
            <?php 
                $flash_type = $_SESSION['flash']['type'] ?? 'success';
                $flash_msg  = $_SESSION['flash']['message'] ?? '';
                unset($_SESSION['flash']); // Menghapus pesan flash agar tidak tampil berulang
            ?>
            <div class="alert alert-<?= htmlspecialchars($flash_type) ?>">
                <span><?= htmlspecialchars($flash_msg) ?></span>
                <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;font-weight:bold;color:inherit;">&times;</button>
            </div>
        <?php // Menampilkan pesan dari parameter query string URL jika ada ?>
        <?php elseif (isset($_GET['status']) && isset($_GET['msg'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_GET['status']) ?>">
                <span><?= htmlspecialchars($_GET['msg']) ?></span>
                <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;font-weight:bold;color:inherit;">&times;</button>
            </div>
        <?php endif; ?>
