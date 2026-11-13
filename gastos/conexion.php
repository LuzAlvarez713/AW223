
<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Datos de conexión
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "gastos_db";

// Crear conexión
$conexion = new mysqli(
    $servidor,
    $usuario,
    $password,
    $base_datos
);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error al conectar con MySQL: " . $conexion->connect_error);
}

// Configurar caracteres
$conexion->set_charset("utf8mb4");
?>
