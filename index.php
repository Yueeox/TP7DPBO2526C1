<?php
// Memulai session jika session PHP belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memuat file class model Kategori, Umkm, dan Menu
require_once __DIR__ . '/class/Kategori.php';
require_once __DIR__ . '/class/Umkm.php';
require_once __DIR__ . '/class/Menu.php';

// Inisialisasi objek dari masing-masing model
$kategoriModel = new Kategori();
$umkmModel     = new Umkm();
$menuModel     = new Menu();

// Mengambil seluruh data kategori, UMKM, dan menu dari database
$kategori_list = $kategoriModel->getAll();
$umkm_list     = $umkmModel->getAll();
$menu_list     = $menuModel->getAll();

// Menghitung total data untuk statistik dashboard
$total_kategori = count($kategori_list);
$total_umkm     = count($umkm_list);
$total_menu     = count($menu_list);

// Mengambil 5 data UMKM pertama untuk preview ringkasan di dashboard
$recent_umkm = array_slice($umkm_list, 0, 5);

// Menentukan judul halaman dan menyertakan header navigasi
$page_title = 'Dashboard';
require_once __DIR__ . '/view/header.php';
?>

<?php // Bagian header halaman dashboard ?>
<div class="page-header">
    <div class="page-title">
        <h1>Dashboard Info UMKM</h1>
        <p>Sistem Informasi Pengelolaan Data UMKM, Kategori, dan Menu Kuliner Kawasan Saparua</p>
    </div>
    <div class="btn-actions">
        <a href="view/umkm.php" class="btn btn-primary">+ Tambah Stand UMKM</a>
    </div>
</div>

<?php // Bagian grid kartu statistik jumlah data ?>
<div class="stats-grid">
    <?php // Kartu statistik total data kategori ?>
    <div class="stat-card">
        <div class="stat-icon primary"></div>
        <div class="stat-info">
            <h3><?= $total_kategori ?></h3>
            <span>Total Kategori</span>
        </div>
    </div>

    <?php // Kartu statistik total data stand UMKM ?>
    <div class="stat-card">
        <div class="stat-icon secondary"></div>
        <div class="stat-info">
            <h3><?= $total_umkm ?></h3>
            <span>Total Stand UMKM</span>
        </div>
    </div>

    <?php // Kartu statistik total data menu kuliner ?>
    <div class="stat-card">
        <div class="stat-icon success"></div>
        <div class="stat-info">
            <h3><?= $total_menu ?></h3>
            <span>Total Menu Kuliner</span>
        </div>
    </div>
</div>

<?php // Bagian kartu tabel ringkasan data stand UMKM ?>
<div class="card">
    <div class="card-header">
        <h2>Stand UMKM Terdaftar</h2>
        <a href="view/umkm.php" class="btn btn-secondary btn-sm">Lihat Semua Data UMKM &rarr;</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Foto</th>
                        <th>Nama Stand</th>
                        <th>Nama Penjaga</th>
                        <th>Kategori</th>
                        <th>Sertifikasi Halal</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php // Menampilkan pesan kosong jika belum ada data UMKM ?>
                    <?php if (empty($recent_umkm)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon"></div>
                                    <p>Belum ada data UMKM yang tersimpan.</p>
                                    <a href="view/umkm.php" class="btn btn-primary btn-sm" style="margin-top: 0.75rem;">+ Tambah UMKM Pertama</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php // Melakukan iterasi untuk setiap baris data UMKM ?>
                        <?php $no = 1; foreach ($recent_umkm as $umkm): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <?php 
                                        // Pengecekan ketersediaan file foto dengan fallback ke file default
                                        $foto_name = 'default.jpg';
                                        // $foto_path = __DIR__ . '/foto_umkm/' . $foto_name;
                                        // if (!file_exists($foto_path)) {
                                        //     $alt_name = pathinfo($foto_name, PATHINFO_FILENAME) . '.JPG';
                                        //     if (file_exists(__DIR__ . '/foto_umkm/' . $alt_name)) {
                                        //         $foto_name = $alt_name;
                                        //     } else {
                                        //         $foto_name = 'default.jpg';
                                        //     }
                                        // }
                                    ?>
                                    <img src="./foto_umkm/<?= htmlspecialchars($foto_name) ?>" 
                                         alt="<?= htmlspecialchars($umkm['nama_stand']) ?>" 
                                         class="umkm-thumb"
                                         onerror="this.src='./foto_umkm/default.jpg'">
                                </td>
                                <td><strong><?= htmlspecialchars($umkm['nama_stand']) ?></strong></td>
                                <td><?= htmlspecialchars($umkm['nama_penjaga']) ?></td>
                                <td>
                                    <span class="badge badge-primary">
                                        <?= htmlspecialchars($umkm['jenis_kategori'] ?? $umkm['jenis'] ?? 'Tanpa Kategori') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php // Menampilkan label status sertifikasi halal ?>
                                    <?php if ($umkm['sertifikasi_halal'] === 'ada'): ?>
                                        <span class="badge badge-success">Halal</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Belum</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php // Tombol link untuk melihat atau mengubah detail data UMKM ?>
                                    <a href="view/umkm.php?action=edit&id=<?= $umkm['id_umkm'] ?>" class="btn btn-warning btn-sm">
                                        Detail / Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Menyertakan footer penutup halaman
require_once __DIR__ . '/view/footer.php';
?>
