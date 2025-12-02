<?php

// Configuración base de la aplicación

// Definir BASE_URL
// Cambiar según tu configuración local
define('BASE_URL', 'http://localhost/Bazar_online/');

// Configuración de base de datos (si la usas)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bazar_online');

// Zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Modo depuración
define('DEBUG', true);

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>