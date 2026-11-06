<?php
// Memulai session jika session PHP belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memuat model class Umkm dan Kategori
require_once __DIR__ . '/../class/Umkm.php';
require_once __DIR__ . '/../class/Kategori.php';

// Inisialisasi objek model Umkm dan Kategori
$umkmModel     = new Umkm();
$kategoriModel = new Kategori();

// 1. Logika penanganan penghapusan data UMKM melalui metode GET
if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Menjalankan method delete pada model Umkm
    if ($umkmModel->delete($id)) {
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data UMKM berhasil dihapus!'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menghapus data UMKM! Pastikan menu terkait sudah dihapus terlebih dahulu.'];
    }
    // Mengalihkan kembali ke halaman kelola UMKM
    header('Location: umkm.php');
    exit;
}

// 2. Logika penanganan form penambahan dan pembaruan data UMKM melalui metode POST
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $action            = $_POST['action'] ?? '';
    $nama_stand        = trim($_POST['nama_stand'] ?? '');
    $nama_penjaga      = trim($_POST['nama_penjaga'] ?? '');
    $foto              = trim($_POST['foto'] ?? '');
    $sertifikasi_halal = $_POST['sertifikasi_halal'] ?? 'tidak';
    $id_kategori       = !empty($_POST['id_kategori']) ? (int)$_POST['id_kategori'] : null;

    // Validasi field nama stand dan nama penjaga wajib diisi
    if ($nama_stand === '' || $nama_penjaga === '') {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Nama stand dan nama penjaga wajib diisi!'];
    // Proses penambahan data UMKM baru
    } elseif ($action === 'create') {
        if ($umkmModel->create($nama_stand, $nama_penjaga, $foto, $sertifikasi_halal, $id_kategori)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data UMKM baru berhasil disimpan!'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menyimpan data UMKM baru!'];
        }
    // Proses pembaruan data UMKM yang sudah ada
    } elseif ($action === 'update' && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        if ($umkmModel->update($id, $nama_stand, $nama_penjaga, $foto, $sertifikasi_halal, $id_kategori)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data UMKM berhasil diperbarui!'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal memperbarui data UMKM!'];
        }
    }

    // Mengalihkan kembali ke halaman kelola UMKM setelah proses POST
    header('Location: umkm.php');
    exit;
}

// 3. Pengecekan mode edit untuk mengambil data detail UMKM berdasarkan ID
$edit_data = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && !empty($_GET['id'])) {
    $edit_data = $umkmModel->getById((int)$_GET['id']);
}

// 4. Mengambil seluruh data UMKM dan data kategori untuk ditampilkan pada form dan tabel
$umkm_list     = $umkmModel->getAll();
$kategori_list = $kategoriModel->getAll();

// Menentukan judul halaman dan menyertakan header navigasi
$page_title = 'Kelola Data UMKM';
require_once __DIR__ . '/header.php';
?>

<?php // Header judul halaman data UMKM ?>
<div class="page-header">
    <div class="page-title">
        <h1>Data UMKM</h1>
        <p>Manajemen data stand UMKM kuliner di kawasan Saparua</p>
    </div>
</div>

<?php // Kartu form untuk menambah atau memperbarui data UMKM ?>
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h2><?= $edit_data ? 'Edit Data UMKM: ' . htmlspecialchars($edit_data['nama_stand']) : 'Tambah Data Stand UMKM Baru' ?></h2>
        <?php // Tombol pembatalan mode edit ?>
        <?php if ($edit_data): ?>
            <a href="umkm.php" class="btn btn-secondary btn-sm">Batal Edit</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <form action="umkm.php" method="POST">
            <?php // Input hidden untuk menentukan jenis aksi (create / update) ?>
            <input type="hidden" name="action" value="<?= $edit_data ? 'update' : 'create' ?>">
            <?php // Input hidden ID UMKM jika dalam mode edit ?>
            <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($edit_data['id_umkm']) ?>">
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
                <?php // Field input nama stand UMKM ?>
                <div class="form-group">
                    <label for="nama_stand">Nama Stand UMKM <span style="color:var(--danger)">*</span></label>
                    <input type="text" 
                           id="nama_stand" 
                           name="nama_stand" 
                           class="form-control" 
                           placeholder="Contoh: Es Jeruk Segar Saparua"
                           value="<?= $edit_data ? htmlspecialchars($edit_data['nama_stand']) : '' ?>" 
                           required>
                </div>

                <?php // Field input nama penjaga atau pemilik stand ?>
                <div class="form-group">
                    <label for="nama_penjaga">Nama Penjaga / Pemilik <span style="color:var(--danger)">*</span></label>
                    <input type="text" 
                           id="nama_penjaga" 
                           name="nama_penjaga" 
                           class="form-control" 
                           placeholder="Contoh: Budi Santoso"
                           value="<?= $edit_data ? htmlspecialchars($edit_data['nama_penjaga']) : '' ?>" 
                           required>
                </div>

                <?php // Pilihan dropdown kategori usaha UMKM secara dinamis ?>
                <div class="form-group">
                    <label for="id_kategori">Kategori UMKM <span style="color:var(--danger)">*</span></label>
                    <select id="id_kategori" name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Jenis Kategori --</option>
                        <?php foreach ($kategori_list as $kat): ?>
                            <?php 
                                $selected = ($edit_data && (int)$edit_data['id_kategori'] === (int)$kat['id_kategori']) ? 'selected' : '';
                            ?>
                            <option value="<?= $kat['id_kategori'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($kat['jenis']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php // Field input nama file foto beserta preview jika data sudah ada ?>
                <div class="form-group">
                    <label for="foto">Nama File Foto</label>
                    <input type="text" 
                           id="foto" 
                           name="foto" 
                           class="form-control" 
                           placeholder="Contoh: sunkis.jpg"
                           value="<?= $edit_data ? htmlspecialchars($edit_data['foto'] ?? '') : '' ?>">
                    <?php // Menampilkan preview foto lama jika ada ?>
                    <?php if ($edit_data && !empty($edit_data['foto'])): ?>
                        <?php
                            $edit_foto = $edit_data['foto'];
                            $check_path = __DIR__ . '/../foto_umkm/' . $edit_foto;
                            if (!file_exists($check_path)) {
                                $alt_file = pathinfo($edit_foto, PATHINFO_FILENAME) . '.JPG';
                                if (file_exists(__DIR__ . '/../foto_umkm/' . $alt_file)) {
                                    $edit_foto = $alt_file;
                                } else {
                                    $edit_foto = 'default.jpg';
                                }
                            }
                        ?>
                        <div class="thumb-preview-box">
                            <img src="../foto_umkm/<?= htmlspecialchars($edit_foto) ?>" alt="Preview" class="thumb-preview-img" onerror="this.src='../foto_umkm/default.jpg'">
                            <div>
                                <small style="display:block;font-weight:600;color:var(--dark);">Foto Saat Ini:</small>
                                <small style="color:var(--gray-500);"><?= htmlspecialchars($edit_data['foto']) ?></small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php // Pilihan radio button status sertifikasi halal ?>
            <div class="form-group" style="margin-top: 0.5rem;">
                <label>Sertifikasi Halal <span style="color:var(--danger)">*</span></label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" 
                                name="sertifikasi_halal" 
                                value="ada" 
                                <?= ($edit_data && $edit_data['sertifikasi_halal'] === 'ada') ? 'checked' : '' ?> 
                                required>
                        <span>Sudah Memiliki Sertifikat Halal</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" 
                                name="sertifikasi_halal" 
                                value="tidak" 
                                <?= (!$edit_data || $edit_data['sertifikasi_halal'] === 'tidak') ? 'checked' : '' ?>>
                        <span>Belum Ada</span>
                    </label>
                </div>
            </div>

            <?php // Tombol submit form dan tombol batalkan pengeditan ?>
            <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <?= $edit_data ? 'Simpan Perubahan UMKM' : 'Tambah Stand UMKM' ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="umkm.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php // Kartu tabel data daftar stand UMKM ?>
<div class="card">
    <div class="card-header">
        <h2>Daftar Stand UMKM</h2>
        <span class="badge badge-primary"><?= count($umkm_list) ?> Total Stand</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Foto</th>
                        <th>Nama Stand</th>
                        <th>Penjaga</th>
                        <th>Kategori</th>
                        <th>Halal</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php // Menampilkan state kosong jika belum ada data UMKM ?>
                    <?php if (empty($umkm_list)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon"></div>
                                    <p>Belum ada data UMKM tersimpan.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php // Iterasi baris data UMKM ?>
                        <?php $no = 1; foreach ($umkm_list as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <?php 
                                        // Pengecekan file foto stand UMKM dengan fallback
                                        $foto_name = 'default.jpg';
                                        $foto_path = __DIR__ . '/../foto_umkm/' . $foto_name;
                                        if (!file_exists($foto_path)) {
                                            $alt_name = pathinfo($foto_name, PATHINFO_FILENAME) . '.JPG';
                                            if (file_exists(__DIR__ . '/../foto_umkm/' . $alt_name)) {
                                                $foto_name = $alt_name;
                                            } else {
                                                $foto_name = 'default.jpg';
                                            }
                                        }
                                    ?>
                                    <img src="../foto_umkm/<?= htmlspecialchars($foto_name) ?>" 
                                         alt="<?= htmlspecialchars($row['nama_stand']) ?>" 
                                         class="umkm-thumb"
                                         onerror="this.src='../foto_umkm/default.jpg'">
                                </td>
                                <td><strong><?= htmlspecialchars($row['nama_stand']) ?></strong></td>
                                <td><?= htmlspecialchars($row['nama_penjaga']) ?></td>
                                <td>
                                    <span class="badge badge-primary">
                                        <?= htmlspecialchars($row['jenis_kategori'] ?? $row['jenis'] ?? 'Tanpa Kategori') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php // Menampilkan badge status sertifikasi halal ?>
                                    <?php if ($row['sertifikasi_halal'] === 'ada'): ?>
                                        <span class="badge badge-success">Halal</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Belum</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php // Tombol aksi edit dan hapus data UMKM dengan konfirmasi ?>
                                    <div class="btn-actions" style="justify-content: center;">
                                        <a href="umkm.php?action=edit&id=<?= $row['id_umkm'] ?>" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            Edit
                                        </a>
                                        <a href="umkm.php?action=delete&id=<?= $row['id_umkm'] ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus stand UMKM: <?= htmlspecialchars(addslashes($row['nama_stand'])) ?>?')"
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
