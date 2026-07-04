<?php
require_once 'functions.php';
require_once 'koneksi.php';
require_login();

if (isset($_POST['submit'])) {
    $nama_kategori = trim($_POST['nama_kategori']);
    
    $errors = array();
    
    if (empty($nama_kategori)) {
        $errors[] = "Nama kategori tidak boleh kosong";
    }
    
    $cek_nama = "SELECT * FROM kategori WHERE nama_kategori = ?";
    $stmt_cek = $conn->prepare($cek_nama);
    $stmt_cek->bind_param("s", $nama_kategori);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();
    
    if ($result_cek->num_rows > 0) {
        $errors[] = "Nama kategori sudah ada, gunakan nama lain";
    }
    
    if (empty($errors)) {
        $sql = "INSERT INTO kategori (nama_kategori) VALUES (?)";
        $stmt_insert = $conn->prepare($sql);
        $stmt_insert->bind_param("s", $nama_kategori);

        if ($stmt_insert->execute()) {
            $_SESSION['pesan'] = "Data kategori berhasil ditambahkan";
            header("Location: kategori.php");
            exit();
        } else {
            $errors[] = "Gagal menambahkan data kategori.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - Sistem Inventori Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
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
                                <i class="bi bi-box"></i> Data Barang
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
                                <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></div>
                                <small class="text-muted"><?php echo ucfirst(htmlspecialchars($_SESSION['level'])); ?></small>
                            </div>
                        </div>
                        <a href="logout.php" class="btn btn-sm btn-danger mt-2 w-100">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Tambah Kategori</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="kategori.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <?php
                if (isset($errors) && !empty($errors)) {
                    echo "<div class='alert alert-danger'>";
                    foreach ($errors as $error) {
                        echo htmlspecialchars($error) . "<br>";
                    }
                    echo "</div>";
                }
                ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Form Tambah Kategori</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="<?php echo isset($_POST['nama_kategori']) ? htmlspecialchars($_POST['nama_kategori']) : ''; ?>" required>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="kategori.php" class="btn btn-secondary">Batal</a>
                                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
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