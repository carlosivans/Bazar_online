<?php
session_start();
require_once '../config/config.php';
require_once '../models/Usuario.php';

$action = $_GET['action'] ?? '';

function redirectWithMessage($location, $type, $message)
{
    $_SESSION[$type] = $message;
    header("Location: $location");
    exit();
}

/* ============================
   LOGIN
   ============================ */
if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            redirectWithMessage(BASE_URL . 'app/views/login.php', 'error', 'Por favor, complete todos los campos.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirectWithMessage(BASE_URL . 'app/views/login.php', 'error', 'Correo electrónico no válido.');
        }

        $usuarioModel = new Usuario();
        $user = $usuarioModel->obtenerPorEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['usuario'] = [
                'id_usuario' => $user['id_usuario'],
                'nombre' => htmlspecialchars($user['nombre'], ENT_QUOTES, 'UTF-8'),
                'email' => $user['email'],
                'rol' => $user['rol']
            ];

            date_default_timezone_set('America/Argentina/Buenos_Aires');
            setcookie('last_login', date('Y-m-d H:i A'), time() + (30 * 24 * 60 * 60), "/");

            header('Location: ' . BASE_URL . 'index.php');
            exit();
        } else {
            redirectWithMessage(BASE_URL . 'app/views/login.php', 'error', 'Correo electrónico o contraseña incorrectos.');
        }
    }

/* ============================
   REGISTRO
   ============================ */
} elseif ($action === 'registro') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password'] ?? '');
        $confirmar_password = trim($_POST['confirmar_password'] ?? '');

        if (empty($nombre) || empty($email) || empty($password) || empty($confirmar_password)) {
            redirectWithMessage(BASE_URL . 'app/views/registro.php', 'error', 'Por favor, complete todos los campos.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirectWithMessage(BASE_URL . 'app/views/registro.php', 'error', 'Correo electrónico no válido.');
        }

        if ($password !== $confirmar_password) {
            redirectWithMessage(BASE_URL . 'app/views/registro.php', 'error', 'Las contraseñas no coinciden.');
        }

        $usuarioModel = new Usuario();

        if ($usuarioModel->existeEmail($email)) {
            redirectWithMessage(BASE_URL . 'app/views/registro.php', 'error', 'El correo electrónico ya está registrado.');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $registrado = $usuarioModel->crear($nombre, $email, $hashedPassword);

        if ($registrado) {
            redirectWithMessage(BASE_URL . 'app/views/login.php', 'success', 'Registro exitoso. Por favor, inicie sesión.');
        } else {
            redirectWithMessage(BASE_URL . 'app/views/registro.php', 'error', 'Error al registrar el usuario. Intente nuevamente.');
        }
    } else {
        header('Location: ' . BASE_URL . 'app/views/registro.php');
        exit();
    }

/* ============================
   LOGOUT
   ============================ */
} elseif ($action === 'logout') {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . 'app/views/login.php');
    exit();

/* ============================
   CONVERTIR A VENDEDOR
   ============================ */
} elseif ($action === 'convertirVendedor') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_usuario = $_POST['id_usuario'] ?? null;
        $response = ['success' => false, 'message' => ''];

        if ($id_usuario === null) {
            $response['message'] = 'ID de usuario no proporcionado.';
            echo json_encode($response);
            exit();
        }

        $usuarioModel = new Usuario();
        $user = $usuarioModel->obtenerPorId($id_usuario);

        if (!$user) {
            $response['message'] = 'Usuario no encontrado.';
            echo json_encode($response);
            exit();
        }

        $updated = $usuarioModel->actualizarRol($id_usuario, 'vendedor');

        if ($updated) {
            if (isset($_SESSION['usuario']) && $_SESSION['usuario']['id_usuario'] == $id_usuario) {
                $_SESSION['usuario']['rol'] = 'vendedor';
            }
            $response['success'] = true;
            $response['message'] = 'Rol actualizado a vendedor correctamente.';
        } else {
            $response['message'] = 'Error al actualizar el rol.';
        }

        echo json_encode($response);
        exit();
    }

} else {
    header('Location: ' . BASE_URL . 'app/views/login.php');
    exit();
}
?>