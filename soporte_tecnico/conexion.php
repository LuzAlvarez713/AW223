
<?php
$host = "localhost";
$basedatos = "soporte_universitario";
$usuario = "root";
$contrasena = "";

try {
    $conexion = new PDO(
        "mysql:host=$host;dbname=$basedatos;charset=utf8mb4",
        $usuario,
        $contrasena
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {
    error_log($e->getMessage());
    exit("Error: no fue posible conectar con la base de datos.");
}
?>
