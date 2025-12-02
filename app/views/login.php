<?php
session_start();

// Incluir configuración
require_once __DIR__ . '/../config/config.php';

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Bazar Online</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/login.css" />
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="<?php echo BASE_URL; ?>app/controllers/UsuarioController.php?action=login">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required />
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required />
            <button type="submit">Entrar</button>
        </form>
        <p>¿No tienes cuenta? <a href="<?php echo BASE_URL; ?>app/views/registro.php">Regístrate aquí</a></p>
        <button type="button" onclick="window.location.href='<?php echo BASE_URL; ?>index.php';" class="btn-regresar">Regresar</button>
    </div>
</body>
</html>