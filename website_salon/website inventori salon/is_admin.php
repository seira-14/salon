<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function is_admin() {
    return isset($_SESSION['level']) && $_SESSION['level'] === 'admin';
}
?>