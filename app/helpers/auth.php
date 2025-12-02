<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function isLoggedIn() { return isset($_SESSION['usuario']); }
function getUserName() { return $_SESSION['usuario']['nombre'] ?? ''; }
function getUserEmail() { return $_SESSION['usuario']['email'] ?? ''; }
function getUserId() { return $_SESSION['usuario']['id_usuario'] ?? null; }
function getUserRol() { return $_SESSION['usuario']['rol'] ?? 'cliente'; }
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/Bazar_online/') . 'app/views/login.php');
        exit();
    }
}
?>
