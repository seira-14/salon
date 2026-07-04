<?php
require_once 'functions.php';
require_once 'koneksi.php';
require_login();

if (isset($_POST['tambah'])) {
    $nama_barang = trim($_POST['nama_barang']);
    $id_kategori = $_POST['id_kategori'];
    $id_supplier = $_POST['id_supplier'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $gambar = '';

    if (!empty($_FILES['gambar']['name'])) {
        $file_name = $_FILES['gambar']['name'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_exts)) {
            $new_file_name = time() . '_' . $file_name;
            $upload_path = "uploads/" . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $gambar = $new_file_name;
            } else {
                $error_message = "Gagal mengupload gambar.";
            }
        } else {
            $error_message = "Hanya file gambar (JPG, JPEG, PNG, GIF) yang diperbolehkan.";
        }
    }

    if (!isset($error_message)) {
        $query = "INSERT INTO barang (nama_barang, id_kategori, id_supplier, stok, harga, gambar) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("siidis", $nama_barang, $id_kategori, $id_supplier, $stok, $harga, $gambar);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Data barang berhasil ditambahkan.";
            header('Location: barang.php');
            exit();
        } else {
            $error_message = "Gagal menambahkan data barang.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - Sistem Inventori Salon</title>
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
                    <h1 class="h2">Tambah Barang</h1>
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
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="harga" name="harga" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gambar" class="form-label">Gambar</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="id_kategori" class="form-label">Kategori</label>
                                    <select class="form-select" id="id_kategori" name="id_kategori" required>
                                        <?php
                                        $kategori_result = $conn->query("SELECT * FROM kategori");
                                        while ($row = $kategori_result->fetch_assoc()) {
                                            echo "<option value='" . $row['id_kategori'] . "'>" . htmlspecialchars($row['nama_kategori']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="id_supplier" class="form-label">Supplier</label>
                                    <select class="form-select" id="id_supplier" name="id_supplier" required>
                                        <?php
                                        $supplier_result = $conn->query("SELECT * FROM supplier");
                                        while ($row = $supplier_result->fetch_assoc()) {
                                            echo "<option value='" . $row['id_supplier'] . "'>" . htmlspecialchars($row['nama_supplier']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="tambah" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Barang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>