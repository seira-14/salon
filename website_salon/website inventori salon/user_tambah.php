<?php
require_once 'functions.php';
require_once 'koneksi.php';
require_login();

if (!is_admin()) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['tambah'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $level = $_POST['level'];
    
    $errors = [];
    
    // 1. Validasi input kosong
    if (empty($username)) $errors[] = "Username tidak boleh kosong.";
    if (empty($password)) $errors[] = "Password tidak boleh kosong.";
    if (empty($nama_lengkap)) $errors[] = "Nama lengkap tidak boleh kosong.";

    // 2. Jika tidak ada error validasi, cek username duplikat
    if (empty($errors)) {
        $cek_username = "SELECT id_user FROM user WHERE username = ?";
        $stmt_cek = $conn->prepare($cek_username);
        $stmt_cek->bind_param("s", $username);
        $stmt_cek->execute();
        $result_cek = $stmt_cek->get_result();
        
        if ($result_cek->num_rows > 0) {
            $errors[] = "Username sudah digunakan, silakan pilih username lain.";
        }
    }

    // 3. Jika tetap tidak ada error, lakukan penyimpanan
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO user (username, password, nama_lengkap, level) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $username, $password_hash, $nama_lengkap, $level);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Data user berhasil ditambahkan.";
            header('Location: user.php');
            exit();
        } else {
            // Tampilkan error database yang spesifik untuk debugging
            $errors[] = "Gagal menambahkan data user. Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - Sistem Inventori Salon</title>
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
                        <a class="nav-link" href="supplier.php">
                            <i class="bi bi-truck"></i> Data Supplier
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="user.php">
                            <i class="bi bi-people"></i> Data User
                        </a>
                    </li>
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
                    <h1 class="h2">Tambah User</h1>
                    <a href="user.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) echo htmlspecialchars($error) . "<br>"; ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="level" class="form-label">Level</label>
                                    <select class="form-select" id="level" name="level" required>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="tambah" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah User
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