<?php
require_once 'functions.php';
require_once 'koneksi.php';

if (is_logged_in()) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $errors = array();
    
    if (empty($username)) {
        $errors[] = "Username tidak boleh kosong";
    }
    if (empty($password)) {
        $errors[] = "Password tidak boleh kosong";
    }
    
    if (empty($errors)) {
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['level'] = $user['level'];
                
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Password salah";
            }
        } else {
            $errors[] = "Username tidak ditemukan";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventori Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <style>
        body {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-logo i {
            font-size: 3rem;
            color: #f5576c;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo text-center mb-4">
            <i class="bi bi-shop"></i>
            <h3 class="mt-2">Inventori Salon</h3>
            <p class="text-muted">Sistem Manajemen Produk</p>
        </div>
        
        <?php
        if (isset($errors) && !empty($errors)) {
            echo "<div class='alert alert-danger' role='alert'>";
            foreach ($errors as $error) {
                echo htmlspecialchars($error) . "<br>";
            }
            echo "</div>";
        }
        ?>
        
        <form method="post" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>
            
            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>