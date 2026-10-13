
<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$mensaje = trim($_POST["mensaje"] ?? "");

if ($nombre === "" || !filter_var($correo, FILTER_VALIDATE_EMAIL) || $mensaje === "") {
    exit("Completa todos los campos correctamente. <a href='index.html#contacto'>Regresar</a>");
}

$sql = "INSERT INTO mensajes (nombre, correo, mensaje)
        VALUES (?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $nombre, $correo, $mensaje);

if ($stmt->execute()) {
    header("Location: admin.php?estado=guardado");
    exit;
} else {
    echo "No se pudo guardar el mensaje.";
}

$stmt->close();
$conexion->close();
?>