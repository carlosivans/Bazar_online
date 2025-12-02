
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir configuración
require_once __DIR__ . '/../config/config.php';

// Funciones de sesión
function isLoggedIn()
{
    return isset($_SESSION['usuario']);
}

function getUserName()
{
    return $_SESSION['usuario']['nombre'] ?? '';
}
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/navbar.css" />
<header class="header">
    <nav class="nav" id="nav-menu">
        <div class="logo">Bazar Online</div>
        <button class="nav-toggle" id="nav-toggle" aria-label="Abrir menú de navegación">
            &#9776;
        </button>
        <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>index.php">Inicio</a>
            <a href="<?php echo BASE_URL; ?>app/views/catalogo.php">Catálogo</a>
            <?php if (!isLoggedIn()): ?>
                <a href="<?php echo BASE_URL; ?>app/views/login.php">Iniciar Sesión</a>
                <a href="<?php echo BASE_URL; ?>app/views/registro.php">Registrarse</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>app/views/perfil.php">Perfil</a>
                <a href="<?php echo BASE_URL; ?>logout.php">Cerrar Sesión</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<script>
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');

    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('nav-menu_visible');
        });
    }
</script>