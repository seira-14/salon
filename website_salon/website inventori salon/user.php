<?php
require_once 'functions.php';
require_once 'koneksi.php';
require_login();

if (!is_admin()) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    
    if ($id_hapus == $_SESSION['user_id']) {
        $_SESSION['error_message'] = "Anda tidak bisa menghapus akun Anda sendiri.";
    } else {
        $query_hapus = "DELETE FROM user WHERE id_user = ?";
        $stmt_hapus = $conn->prepare($query_hapus);
        $stmt_hapus->bind_param("i", $id_hapus);

        if ($stmt_hapus->execute()) {
            $_SESSION['success_message'] = "Data user berhasil dihapus.";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus data user.";
        }
    }
    
    header('Location: user.php');
    exit();
}

 $query = "SELECT id_user, username, nama_lengkap, level FROM user ORDER BY nama_lengkap ASC";
 $result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User - Sistem Inventori Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <?php if (is_admin()): ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="user.php">
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

        <div class="main-wrapper">
            <main class="content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Data User</h1>
                    <a href="user_tambah.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah User
                    </a>
                </div>

                <?php
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                    echo $_SESSION['success_message'];
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                    unset($_SESSION['success_message']);
                }

                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                    echo $_SESSION['error_message'];
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                    unset($_SESSION['error_message']);
                }
                ?>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Level</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $no++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                                            echo "<td><span class='badge bg-secondary'>" . htmlspecialchars(ucfirst($row['level'])) . "</span></td>";
                                            echo "<td>";
                                            
                                            if ($row['id_user'] != $_SESSION['user_id']) {
                                                echo "<div class='btn-group'>";
                                                echo "<a href='user_edit.php?id=" . $row['id_user'] . "' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i></a>";
                                                echo "<a href='user.php?hapus=" . $row['id_user'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus user ini?\")'><i class='bi bi-trash'></i></a>";
                                                echo "</div>";
                                            } else {
                                                echo "<span class='badge bg-info'>Akun Anda</span>";
                                            }
                                            
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>Tidak ada data user</td></tr>";
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap/5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>