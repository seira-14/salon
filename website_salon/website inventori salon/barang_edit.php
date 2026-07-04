<?php
require_once 'functions.php';
require_once 'koneksi.php';
require_login();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "ID barang tidak ditemukan.";
    header('Location: barang.php');
    exit();
}
 $id_barang = (int)$_GET['id'];

 $query = "SELECT * FROM barang WHERE id_barang = ?";
 $stmt = $conn->prepare($query);
 $stmt->bind_param("i", $id_barang);
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['error_message'] = "Data barang tidak ditemukan.";
    header('Location: barang.php');
    exit();
}
 $barang = $result->fetch_assoc();

 $kategori_result = $conn->query("SELECT * FROM kategori");
 $supplier_result = $conn->query("SELECT * FROM supplier");

if (isset($_POST['update'])) {
    $nama_barang = trim($_POST['nama_barang']);
    $id_kategori = $_POST['id_kategori'];
    $id_supplier = $_POST['id_supplier'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $gambar_lama = $barang['gambar'];
    $gambar = $gambar_lama;

    if (!empty($_FILES['gambar']['name'])) {
        $file_name = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // --- DIUBAH: Tambahkan 'webp' ---
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif', 'webp'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $new_gambar_name = time() . '_' . $file_name;
            $path = "uploads/" . $new_gambar_name;
            
            if (move_uploaded_file($tmp_name, $path)) {
                if (!empty($gambar_lama) && file_exists("uploads/" . $gambar_lama)) {
                    unlink("uploads/" . $gambar_lama);
                }
                $gambar = $new_gambar_name;
            } else {
                $error_message = "Gagal mengupload gambar.";
            }
        } else {
            // --- DIUBAH: Tambahkan WEBP ke pesan error ---
           $error_message = "Hanya file gambar (JPG, JPEG, PNG, GIF, JFIF, WEBP) yang diperbolehkan.";
        }
    }

    if (!isset($error_message)) {
        $update_query = "UPDATE barang SET nama_barang = ?, id_kategori = ?, id_supplier = ?, stok = ?, harga = ?, gambar = ? WHERE id_barang = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("siidisi", $nama_barang, $id_kategori, $id_supplier, $stok, $harga, $gambar, $id_barang);

        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "Data barang berhasil diperbarui.";
            header('Location: barang.php');
            exit();
        } else {
            $error_message = "Gagal memperbarui data barang.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Sistem Inventori Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    <div class="main-container">
        <nav class="sidebar">
            <div class="position-sticky pt-3">
                <h5 class="text-center mb-4 fw-bold">Menu Navigasi</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="barang.php">
                            <i class="bi bi-box-seam"></i> Data Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kategori.php">
                            <i class="bi bi-tags"></i> Data Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="supplier.php">
                            <i class="bi bi-truck"></i> Data Supplier
                        </a>
                    </li>
                    <?php if (is_admin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="user.php">
                            <i class="bi bi-people"></i> Data User
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <div class="user-profile">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle fs-4 me-2"></i>
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></div>
                            <small class="text-muted"><?= ucfirst(htmlspecialchars($_SESSION['level'])) ?></small>
                        </div>
                    </div>
                    <a href="logout.php" class="btn btn-sm btn-danger mt-2 w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <div class="main-wrapper">
            <main class="content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Barang</h1>
                    <a href="barang.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_barang" class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" value="<?= htmlspecialchars($barang['stok']) ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="harga" name="harga" value="<?= htmlspecialchars($barang['harga']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gambar" class="form-label">Gambar</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                    <small class="text-muted">Gambar saat ini: <?= htmlspecialchars($barang['gambar'] ?? 'Tidak ada') ?></small>
                                    <?php if (!empty($barang['gambar'])): ?>
                                        <br><img src="uploads/<?= htmlspecialchars($barang['gambar']) ?>" width="100" alt="Gambar Barang">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="id_kategori" class="form-label">Kategori</label>
                                    <select class="form-select" id="id_kategori" name="id_kategori" required>
                                        <?php 
                                        $kategori_result->data_seek(0);
                                        while ($row = $kategori_result->fetch_assoc()): ?>
                                            <option value="<?= $row['id_kategori'] ?>" <?= ($barang['id_kategori'] == $row['id_kategori']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($row['nama_kategori']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="id_supplier" class="form-label">Supplier</label>
                                    <select class="form-select" id="id_supplier" name="id_supplier" required>
                                        <?php 
                                        $supplier_result->data_seek(0);
                                        while ($row = $supplier_result->fetch_assoc()): ?>
                                            <option value="<?= $row['id_supplier'] ?>" <?= ($barang['id_supplier'] == $row['id_supplier']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($row['nama_supplier']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="update" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>