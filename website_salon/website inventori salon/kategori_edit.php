<?php
require_once 'functions.php';
require_once 'koneksi.php';
require_login();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "ID kategori tidak ditemukan.";
    header('Location: kategori.php');
    exit();
}
 $id_kategori = (int)$_GET['id'];

 $query = "SELECT * FROM kategori WHERE id_kategori = ?";
 $stmt = $conn->prepare($query);
 $stmt->bind_param("i", $id_kategori);
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['error_message'] = "Data kategori tidak ditemukan.";
    header('Location: kategori.php');
    exit();
}
 $kategori = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $nama_kategori_baru = trim($_POST['nama_kategori']);
    
    if (empty($nama_kategori_baru)) {
        $error_message = "Nama kategori tidak boleh kosong.";
    } else {
        $cek_nama = "SELECT * FROM kategori WHERE nama_kategori = ? AND id_kategori != ?";
        $stmt_cek = $conn->prepare($cek_nama);
        $stmt_cek->bind_param("si", $nama_kategori_baru, $id_kategori);
        $stmt_cek->execute();
        $result_cek = $stmt_cek->get_result();
        
        if ($result_cek->num_rows > 0) {
            $error_message = "Nama kategori sudah ada, gunakan nama lain.";
        } else {
            $update_query = "UPDATE kategori SET nama_kategori = ? WHERE id_kategori = ?";
            $stmt_update = $conn->prepare($update_query);
            $stmt_update->bind_param("si", $nama_kategori_baru, $id_kategori);

            if ($stmt_update->execute()) {
                $_SESSION['success_message'] = "Data kategori berhasil diperbarui.";
                header('Location: kategori.php');
                exit();
            } else {
                $error_message = "Gagal memperbarui data kategori.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - Sistem Inventori Salon</title>
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
                        <a class="nav-link" href="barang.php">
                            <i class="bi bi-box-seam"></i> Data Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="kategori.php">
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
                    <h1 class="h2">Edit Kategori</h1>
                    <a href="kategori.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="<?= htmlspecialchars($kategori['nama_kategori']) ?>" required>
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