<?php
require_once 'functions.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang - Sistem Inventori Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    <div class="main-container">
        <!-- SIDEBAR - Copy dari index.php -->
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
                            <i class="bi bi-box"></i> Data Barang
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
                    <?php if (function_exists('is_admin') && is_admin()): ?>
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
                            <div class="fw-bold"><?php echo $_SESSION['nama_lengkap']; ?></div>
                            <small class="text-muted"><?php echo ucfirst($_SESSION['level']); ?></small>
                        </div>
                    </div>
                    <a href="logout.php" class="btn btn-sm btn-danger mt-2 w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- MAIN WRAPPER -->
        <div class="main-wrapper">
            <main class="content">
                <!-- Page Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Data Barang</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="barang_tambah.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Barang
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>GAMBAR</th>
                                        <th>NAMA BARANG</th>
                                        <th>KATEGORI</th>
                                        <th>SUPPLIER</th>
                                        <th>STOK</th>
                                        <th>HARGA</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query data barang
                                    $query = "SELECT b.*, k.nama_kategori, s.nama_supplier 
                                             FROM barang b 
                                             JOIN kategori k ON b.id_kategori = k.id_kategori 
                                             JOIN supplier s ON b.id_supplier = s.id_supplier 
                                             ORDER BY b.id_barang DESC";
                                    $result = $conn->query($query);
                                    
                                    if ($result && $result->num_rows > 0) {
                                        $no = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $no++ . "</td>";
                                            echo "<td>";
                                            if (!empty($row['gambar'])) {
                                                echo "<img src='uploads/" . htmlspecialchars($row['gambar']) . "' width='50' height='50' class='img-thumbnail rounded'>";
                                            } else {
                                                echo "<img src='https://via.placeholder.com/50' class='img-thumbnail rounded'>";
                                            }
                                            echo "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama_kategori']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama_supplier']) . "</td>";
                                            
                                            // Badge untuk stok
                                            $badge_class = $row['stok'] < 10 ? 'bg-danger' : 'bg-info';
                                            echo "<td><span class='badge " . $badge_class . "'>" . htmlspecialchars($row['stok']) . "</span></td>";
                                            
                                            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                            echo "<td>
                                                    <div class='btn-group'>
                                                        <a href='barang_edit.php?id=" . htmlspecialchars($row['id_barang']) . "' class='btn btn-sm btn-warning'>
                                                            <i class='bi bi-pencil'></i>
                                                        </a>
                                                        <a href='barang_hapus.php?id=" . htmlspecialchars($row['id_barang']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>
                                                            <i class='bi bi-trash'></i>
                                                        </a>
                                                    </div>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>Tidak ada data</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>