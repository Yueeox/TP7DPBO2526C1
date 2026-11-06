<?php
// Memulai session jika session PHP belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memuat model class Kategori
require_once __DIR__ . '/../class/Kategori.php';

// Inisialisasi objek model Kategori
$kategoriModel = new Kategori();

// 1. Logika penanganan penghapusan data kategori melalui metode GET
if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Mengeksekusi method delete pada model Kategori
    if ($kategoriModel->delete($id)) {
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data kategori berhasil dihapus!'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menghapus kategori! Data mungkin sedang digunakan oleh UMKM.'];
    }
    // Mengalihkan kembali ke halaman kelola kategori
    header('Location: kategori.php');
    exit;
}

// 2. Logika penanganan form penambahan dan pembaruan data kategori melalui metode POST
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $action = $_POST['action'] ?? '';
    $jenis  = trim($_POST['jenis'] ?? '');

    // Validasi input data jenis kategori tidak boleh kosong
    if ($jenis === '') {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Nama jenis kategori tidak boleh kosong!'];
    // Proses penambahan data kategori baru
    } elseif ($action === 'create') {
        if ($kategoriModel->create($jenis)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kategori baru berhasil ditambahkan!'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menambahkan kategori baru!'];
        }
    // Proses pembaruan data kategori yang sudah ada
    } elseif ($action === 'update' && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        if ($kategoriModel->update($id, $jenis)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data kategori berhasil diperbarui!'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal memperbarui kategori!'];
        }
    }

    // Mengalihkan kembali ke halaman kelola kategori setelah proses POST
    header('Location: kategori.php');
    exit;
}

// 3. Pengecekan mode edit untuk mengambil data kategori lama berdasarkan ID
$edit_data = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && !empty($_GET['id'])) {
    $edit_data = $kategoriModel->getById((int)$_GET['id']);
}

// 4. Mengambil seluruh daftar data kategori dari database
$kategori_list = $kategoriModel->getAll();

// Menentukan judul halaman dan menyertakan header navigasi
$page_title = 'Kelola Data Kategori';
require_once __DIR__ . '/header.php';
?>

<?php // Header halaman kategori ?>
<div class="page-header">
    <div class="page-title">
        <h1>Data Kategori</h1>
        <p>Manajemen kategori produk dan makanan UMKM Saparua</p>
    </div>
</div>

<div class="grid-2">
    <?php // Kartu form untuk tambah atau edit kategori ?>
    <div class="card">
        <div class="card-header">
            <h2><?= $edit_data ? 'Edit Kategori' : 'Tambah Kategori Baru' ?></h2>
            <?php // Tombol batal edit jika dalam mode pengeditan ?>
            <?php if ($edit_data): ?>
                <a href="kategori.php" class="btn btn-secondary btn-sm">Batal</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <form action="kategori.php" method="POST">
                <?php // Input hidden untuk menentukan jenis aksi (create / update) ?>
                <input type="hidden" name="action" value="<?= $edit_data ? 'update' : 'create' ?>">
                <?php // Input hidden ID kategori jika sedang dalam mode edit ?>
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($edit_data['id_kategori']) ?>">
                <?php endif; ?>

                <?php // Field input nama jenis kategori ?>
                <div class="form-group">
                    <label for="jenis">Jenis Kategori <span style="color:var(--danger)">*</span></label>
                    <input type="text" 
                           id="jenis" 
                           name="jenis" 
                           class="form-control" 
                           placeholder="Contoh: Makanan Berat, Minuman..."
                           value="<?= $edit_data ? htmlspecialchars($edit_data['jenis']) : '' ?>" 
                           required>
                </div>

                <?php // Tombol submit untuk menyimpan atau memperbarui kategori ?>
                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary btn-block">
                        <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Kategori' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php // Kartu tabel yang menampilkan daftar kategori ?>
    <div class="card">
        <div class="card-header">
            <h2>Daftar Kategori</h2>
            <span class="badge badge-primary"><?= count($kategori_list) ?> Total Kategori</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th style="width: 100px;">ID</th>
                            <th>Jenis Kategori</th>
                            <th style="width: 160px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php // Menampilkan state kosong jika belum ada data ?>
                        <?php if (empty($kategori_list)): ?>
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">📂</div>
                                        <p>Belum ada data kategori tersimpan.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php // Iterasi baris data kategori ?>
                            <?php $no = 1; foreach ($kategori_list as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong>#<?= htmlspecialchars($row['id_kategori']) ?></strong></td>
                                    <td>
                                        <span class="badge badge-primary">
                                            <?= htmlspecialchars($row['jenis']) ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php // Tombol aksi edit dan hapus kategori ?>
                                        <div class="btn-actions" style="justify-content: center;">
                                            <a href="kategori.php?action=edit&id=<?= $row['id_kategori'] ?>" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                Edit
                                            </a>
                                            <a href="kategori.php?action=delete&id=<?= $row['id_kategori'] ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus kategori: <?= htmlspecialchars(addslashes($row['jenis'])) ?>?')"
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
</div>

<?php
// Menyertakan footer penutup halaman
require_once __DIR__ . '/footer.php';
?>
