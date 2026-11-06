<?php
// Memulai session jika session PHP belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memuat model class Menu dan Umkm
require_once __DIR__ . '/../class/Menu.php';
require_once __DIR__ . '/../class/Umkm.php';

// Inisialisasi objek model Menu dan Umkm
$menuModel = new Menu();
$umkmModel = new Umkm();

// 1. Logika penanganan penghapusan data menu melalui metode GET
if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Menjalankan method delete pada model Menu
    if ($menuModel->delete($id)) {
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data menu berhasil dihapus!'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menghapus data menu!'];
    }
    // Mengalihkan kembali ke halaman kelola menu
    header('Location: menu.php');
    exit;
}

// 2. Logika penanganan form penambahan dan pembaruan data menu melalui metode POST
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $action     = $_POST['action'] ?? '';
    $nama_menu  = trim($_POST['nama_menu'] ?? '');
    $harga_menu = trim($_POST['harga_menu'] ?? '');
    $id_umkm    = (int)($_POST['id_umkm'] ?? 0);
    $deskripsi  = trim($_POST['deskripsi'] ?? '');

    // Validasi field wajib diisi
    if ($nama_menu === '' || $harga_menu === '' || empty($id_umkm)) {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Nama menu, harga, dan stand UMKM wajib diisi!'];
    // Validasi format harga harus bernilai numerik dan lebih dari nol
    } elseif (!is_numeric($harga_menu) || (float)$harga_menu <= 0) {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Harga menu harus berupa angka positif!'];
    // Proses penambahan menu baru
    } elseif ($action === 'create') {
        if ($menuModel->create($nama_menu, $harga_menu, $id_umkm, $deskripsi)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Menu baru berhasil ditambahkan!'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menambahkan menu baru!'];
        }
    // Proses pembaruan data menu yang sudah ada
    } elseif ($action === 'update' && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        if ($menuModel->update($id, $nama_menu, $harga_menu, $id_umkm, $deskripsi)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data menu berhasil diperbarui!'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal memperbarui menu!'];
        }
    }

    // Mengalihkan kembali ke halaman kelola menu setelah proses POST
    header('Location: menu.php');
    exit;
}

// 3. Pengecekan mode edit untuk mengambil data detail menu berdasarkan ID
$edit_data = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && !empty($_GET['id'])) {
    $edit_data = $menuModel->getById((int)$_GET['id']);
}

// 4. Mengambil data seluruh menu dan stand UMKM untuk kebutuhan view dan dropdown
$menu_list = $menuModel->getAll();
$umkm_list = $umkmModel->getAll();

// Menentukan judul halaman dan menyertakan header navigasi
$page_title = 'Kelola Data Menu';
require_once __DIR__ . '/header.php';
?>

<?php // Header halaman data menu ?>
<div class="page-header">
    <div class="page-title">
        <h1>Data Menu</h1>
        <p>Manajemen menu kuliner dan minuman UMKM kawasan Saparua</p>
    </div>
</div>

<?php // Kartu form untuk menambah atau mengedit menu ?>
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h2><?= $edit_data ? 'Edit Menu: ' . htmlspecialchars($edit_data['nama_menu']) : 'Tambah Menu Baru' ?></h2>
        <?php // Tombol batal edit jika sedang berada dalam mode edit ?>
        <?php if ($edit_data): ?>
            <a href="menu.php" class="btn btn-secondary btn-sm">Batal Edit</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <form action="menu.php" method="POST">
            <?php // Input hidden untuk menentukan jenis aksi (create / update) ?>
            <input type="hidden" name="action" value="<?= $edit_data ? 'update' : 'create' ?>">
            <?php // Input hidden ID menu jika dalam mode edit ?>
            <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($edit_data['id_menu']) ?>">
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
                <?php // Field input nama menu ?>
                <div class="form-group">
                    <label for="nama_menu">Nama Menu <span style="color:var(--danger)">*</span></label>
                    <input type="text" 
                           id="nama_menu" 
                           name="nama_menu" 
                           class="form-control" 
                           placeholder="Contoh: Es Teh Manis Jumbo"
                           value="<?= $edit_data ? htmlspecialchars($edit_data['nama_menu']) : '' ?>" 
                           required>
                </div>

                <?php // Field input harga menu ?>
                <div class="form-group">
                    <label for="harga_menu">Harga Menu (Rp) <span style="color:var(--danger)">*</span></label>
                    <input type="number" 
                           id="harga_menu" 
                           name="harga_menu" 
                           class="form-control" 
                           placeholder="Contoh: 15000"
                           min="500" 
                           step="500"
                           value="<?= $edit_data ? htmlspecialchars($edit_data['harga_menu']) : '' ?>" 
                           required>
                </div>

                <?php // Pilihan dropdown stand UMKM secara dinamis dari tabel umkm ?>
                <div class="form-group">
                    <label for="id_umkm">Stand UMKM <span style="color:var(--danger)">*</span></label>
                    <select id="id_umkm" name="id_umkm" class="form-control" required>
                        <option value="">-- Pilih Stand UMKM --</option>
                        <?php foreach ($umkm_list as $umkm): ?>
                            <?php 
                                $selected = ($edit_data && (int)$edit_data['id_umkm'] === (int)$umkm['id_umkm']) ? 'selected' : '';
                            ?>
                            <option value="<?= $umkm['id_umkm'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($umkm['nama_stand']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <?php // Field textarea untuk deskripsi menu ?>
            <div class="form-group">
                <label for="deskripsi">Deskripsi / Cita Rasa Menu</label>
                <textarea id="deskripsi" 
                          name="deskripsi" 
                          class="form-control" 
                          placeholder="Contoh: Manis, Segar, Gurih, Pilihan rasa vanila atau melati"><?= $edit_data ? htmlspecialchars($edit_data['deskripsi'] ?? '') : '' ?></textarea>
            </div>

            <?php // Tombol aksi submit form dan tombol pembatalan ?>
            <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <?= $edit_data ? 'Simpan Perubahan Menu' : 'Tambah Menu' ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="menu.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php // Kartu tabel data daftar menu ?>
<div class="card">
    <div class="card-header">
        <h2>Daftar Menu</h2>
        <span class="badge badge-primary"><?= count($menu_list) ?> Total Menu</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Menu</th>
                        <th>Stand UMKM</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php // Menampilkan state kosong jika belum ada data menu ?>
                    <?php if (empty($menu_list)): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon"></div>
                                    <p>Belum ada data menu tersimpan.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php // Iterasi baris data menu ?>
                        <?php $no = 1; foreach ($menu_list as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($row['nama_menu']) ?></strong></td>
                                <td>
                                    <span class="badge badge-gray">
                                        <?= htmlspecialchars($row['nama_stand'] ?? 'Tidak Terkait') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php // Menampilkan harga yang diformat ke format Rupiah ?>
                                    <span style="font-weight: 600; color: #047857;">
                                        Rp <?= number_format((float)$row['harga_menu'], 0, ',', '.') ?>
                                    </span>
                                </td>
                                <td>
                                    <small style="color: var(--gray-500);">
                                        <?= htmlspecialchars($row['deskripsi'] ?? '-') ?>
                                    </small>
                                </td>
                                <td style="text-align: center;">
                                    <?php // Tombol aksi edit dan hapus menu dengan konfirmasi ?>
                                    <div class="btn-actions" style="justify-content: center;">
                                        <a href="menu.php?action=edit&id=<?= $row['id_menu'] ?>" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            Edit
                                        </a>
                                        <a href="menu.php?action=delete&id=<?= $row['id_menu'] ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus menu: <?= htmlspecialchars(addslashes($row['nama_menu'])) ?>?')"
                                           title="Hapus">
                                            Hapus
                                        </a>
                                    </div>
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
require_once __DIR__ . '/footer.php';
?>
