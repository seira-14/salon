<?php
require_once 'functions.php';
require_once 'koneksi.php';
require_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Inventori Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    <div class="main-container">
        <!-- SIDEBAR - Struktur yang konsisten dengan file lain -->
        <nav class="sidebar">
            <div class="position-sticky pt-3">
                <h5 class="text-center mb-4 fw-bold">Menu Navigasi</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
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

        <!-- MAIN WRAPPER - Struktur yang konsisten dengan file lain -->
        <div class="main-wrapper">
            <main class="content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard Inventori</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="text-muted">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></strong>!</span>
                    </div>
                </div>

                <?php
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $_SESSION['success_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                    unset($_SESSION['success_message']);
                }
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                    unset($_SESSION['error_message']);
                }
                ?>

                <!-- DASHBOARD STATS GRID - KEMBALI KE WARNA-WARNI -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Total Barang</h5>
                                    <?php
                                    $query = "SELECT COUNT(*) as total FROM barang";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo "<h2>" . $row['total'] . "</h2>";
                                    ?>
                                </div>
                                <i class="bi bi-box-seam fs-1 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Total Kategori</h5>
                                    <?php
                                    $query = "SELECT COUNT(*) as total FROM kategori";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo "<h2>" . $row['total'] . "</h2>";
                                    ?>
                                </div>
                                <i class="bi bi-tags fs-1 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-info">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Total Supplier</h5>
                                    <?php
                                    $query = "SELECT COUNT(*) as total FROM supplier";
                                    $result = $conn->query($query);
                                    $row = $result->fetch_assoc();
                                    echo "<h2>" . $row['total'] . "</h2>";
                                    ?>
                                </div>
                                <i class="bi bi-truck fs-1 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sisanya tetap sama -->
                <?php
                $limit = 5;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $page = max(1, $page);

                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
                $maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 999999999;

                $sql_total = "SELECT COUNT(*) as total FROM barang b 
                              JOIN kategori k ON b.id_kategori = k.id_kategori 
                              JOIN supplier s ON b.id_supplier = s.id_supplier 
                              WHERE b.stok < 10 AND b.nama_barang LIKE ? AND b.harga >= ? AND b.harga <= ?";
                $stmt_total = $conn->prepare($sql_total);
                $search_term = '%' . $search . '%';
                $stmt_total->bind_param("sii", $search_term, $minPrice, $maxPrice);
                $stmt_total->execute();
                $total_result_stok = $stmt_total->get_result();
                $total_row_stok = $total_result_stok->fetch_assoc();
                $total_records_stok = $total_row_stok['total'];

                $total_pages = ceil($total_records_stok / $limit);
                $total_pages_safe = $total_pages > 0 ? $total_pages : 1;
                $page = min($page, $total_pages_safe);
                $offset = ($page - 1) * $limit;

                $query_stok_rendah = "SELECT b.*, k.nama_kategori, s.nama_supplier 
                             FROM barang b 
                             JOIN kategori k ON b.id_kategori = k.id_kategori 
                             JOIN supplier s ON b.id_supplier = s.id_supplier 
                             WHERE b.stok < 10 AND b.nama_barang LIKE ? AND b.harga >= ? AND b.harga <= ?
                             ORDER BY b.stok ASC 
                             LIMIT ? OFFSET ?";
                $stmt_data = $conn->prepare($query_stok_rendah);
                $stmt_data->bind_param("siiii", $search_term, $minPrice, $maxPrice, $limit, $offset);
                $stmt_data->execute();
                $result_stok_rendah = $stmt_data->get_result();
                ?>
                
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0"><i class="bi bi-exclamation-triangle-fill text-danger"></i> Barang dengan Stok Rendah</h4>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3 filter-section">
                                    <div class="col-md-4">
                                        <input type="text" id="searchInput" class="form-control" placeholder="Cari nama barang..." value="<?= htmlspecialchars($search) ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" id="minPrice" class="form-control" placeholder="Harga Min" value="<?= htmlspecialchars($minPrice) ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" id="maxPrice" class="form-control" placeholder="Harga Max" value="<?= htmlspecialchars($maxPrice == 999999999 ? '' : $maxPrice) ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <button id="filterButton" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Gambar</th>
                                                <th>Nama Barang</th>
                                                <th>Kategori</th>
                                                <th>Stok</th>
                                                <th>Harga</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if ($result_stok_rendah && $result_stok_rendah->num_rows > 0): ?>
                                                <?php while ($row = $result_stok_rendah->fetch_assoc()): ?>
                                                    <tr>
                                                        <td>
                                                            <?php if (!empty($row['gambar'])): ?>
                                                                <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_barang']) ?>" width="50">
                                                            <?php else: ?>
                                                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                                        <td><span class="badge bg-danger"><?= htmlspecialchars($row['stok']) ?></span></td>
                                                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                                        <td>
                                                            <a href="barang_edit.php?id=<?= $row['id_barang'] ?>" class="btn btn-sm btn-warning" title="Edit Barang">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr><td colspan="6" class="text-center text-muted">Tidak ada barang dengan stok rendah.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <?php if ($total_pages > 1): ?>
                                <nav aria-label="Pagination Stok Rendah" class="mt-3">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&min_price=<?= $minPrice ?>&max_price=<?= $maxPrice ?>">Sebelumnya</a>
                                        </li>
                                        <?php 
                                        $start_page = max(1, $page - 2);
                                        $end_page = min($total_pages, $page + 2);
                                        
                                        if ($page <= 3) $end_page = min(5, $total_pages);
                                        if ($page >= $total_pages - 2) $start_page = max(1, $total_pages - 4);
                                        
                                        if ($start_page > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="?page=1&search=' . urlencode($search) . '&min_price=' . $minPrice . '&max_price=' . $maxPrice . '">1</a></li>';
                                            if ($start_page > 2) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                        }
                                        
                                        for ($i = $start_page; $i <= $end_page; $i++): ?>
                                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&min_price=<?= $minPrice ?>&max_price=<?= $maxPrice ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; 
                                        
                                        if ($end_page < $total_pages) {
                                            if ($end_page < $total_pages - 1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&search=' . urlencode($search) . '&min_price=' . $minPrice . '&max_price=' . $maxPrice . '">' . $total_pages . '</a></li>';
                                        }
                                        ?>
                                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&min_price=<?= $minPrice ?>&max_price=<?= $maxPrice ?>">Selanjutnya</a>
                                        </li>
                                    </ul>
                                </nav>
                                <?php endif; ?>
                                <?php 
                                $stmt_total->close();
                                $stmt_data->close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $query_terbaru = "SELECT b.*, k.nama_kategori FROM barang b LEFT JOIN kategori k ON b.id_kategori = k.id_kategori ORDER BY b.id_barang DESC LIMIT 3";
                $result_terbaru = $conn->query($query_terbaru);
                ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0"><i class="bi bi-clock-history"></i> Barang Terbaru Ditambahkan</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php if ($result_terbaru && $result_terbaru->num_rows > 0): ?>
                                        <?php while ($row = $result_terbaru->fetch_assoc()): ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="card h-100">
                                                    <?php if (!empty($row['gambar'])): ?>
                                                        <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top p-3" style="height: 200px; object-fit: contain;" alt="<?= htmlspecialchars($row['nama_barang']) ?>">
                                                    <?php else: ?>
                                                        <img src="https://via.placeholder.com/300x200" class="card-img-top p-3" alt="No Image">
                                                    <?php endif; ?>
                                                    <div class="card-body">
                                                        <h6 class="card-title"><?= htmlspecialchars($row['nama_barang']) ?></h6>
                                                        <p class="card-text">
                                                            <small class="text-muted">Kategori: <?= htmlspecialchars($row['nama_kategori'] ?? 'Tidak ada') ?></small><br>
                                                            <strong>Rp <?= number_format($row['harga'], 0, ',', '.') ?></strong>
                                                        </p>
                                                        <a href="barang_edit.php?id=<?= $row['id_barang'] ?>" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p class="text-muted col-12 text-center">Belum ada barang yang ditambahkan.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>