<?php
require_once 'functions.php';
require_once 'koneksi.php';
require_login();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "ID supplier tidak ditemukan.";
    header('Location: supplier.php');
    exit();
}
 $id_supplier = (int)$_GET['id'];

 $query = "SELECT * FROM supplier WHERE id_supplier = ?";
 $stmt = $conn->prepare($query);
 $stmt->bind_param("i", $id_supplier);
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['error_message'] = "Data supplier tidak ditemukan.";
    header('Location: supplier.php');
    exit();
}
 $supplier = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $nama_supplier = trim($_POST['nama_supplier']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    
    $errors = [];
    if (empty($nama_supplier)) $errors[] = "Nama supplier tidak boleh kosong.";
    if (empty($telepon)) $errors[] = "Telepon tidak boleh kosong.";
    if (empty($alamat)) $errors[] = "Alamat tidak boleh kosong.";
    
    if (empty($errors)) {
        $cek_nama = "SELECT id_supplier FROM supplier WHERE nama_supplier = ? AND id_supplier != ?";
        $stmt_cek = $conn->prepare($cek_nama);
        $stmt_cek->bind_param("si", $nama_supplier, $id_supplier);
        $stmt_cek->execute();
        $result_cek = $stmt_cek->get_result();
        
        if ($result_cek->num_rows > 0) {
            $errors[] = "Nama supplier sudah ada, gunakan nama lain.";
        } 
    }

    if (empty($errors)) {
        $update_query = "UPDATE supplier SET nama_supplier = ?, telepon = ?, alamat = ? WHERE id_supplier = ?";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bind_param("sssi", $nama_supplier, $telepon, $alamat, $id_supplier);

        if ($stmt_update->execute()) {
            $_SESSION['success_message'] = "Data supplier berhasil diperbarui.";
            header('Location: supplier.php');
            exit();
        } else {
            $error_message = "Gagal memperbarui data supplier: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier - Sistem Inventori Salon</title>
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
                        <a class="nav-link" href="kategori.php">
                            <i class="bi bi-tags"></i> Data Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="supplier.php">
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
                    <h1 class="h2">Edit Supplier</h1>
                    <a href="supplier.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) echo htmlspecialchars($error) . "<br>"; ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="nama_supplier" class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="<?= htmlspecialchars($supplier['nama_supplier']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="telepon" name="telepon" value="<?= htmlspecialchars($supplier['telepon']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($supplier['alamat']) ?></textarea>
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